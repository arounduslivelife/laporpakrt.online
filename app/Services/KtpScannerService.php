<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use thiagoalessio\TesseractOCR\TesseractOCR;
use thiagoalessio\TesseractOCR\TesseractOcrException;

class KtpScannerService
{
    /**
     * Scan a KTP image and extract identity fields.
     *
     * @param UploadedFile|string $image Either a Livewire/Http uploaded file or a storage path.
     * @return array<string, string|null>
     */
    public function scan($image): array
    {
        $sourcePath = $this->resolveSourcePath($image);

        if (! file_exists($sourcePath)) {
            throw new \RuntimeException('File KTP tidak ditemukan.');
        }

        $binary = $this->getBinaryPath();

        if (! file_exists($binary)) {
            throw new \RuntimeException(
                'Tesseract OCR binary tidak ditemukan di: ' . $binary .
                '. Silakan install Tesseract OCR atau sesuaikan TESSERACT_PATH di .env.'
            );
        }

        $processedPath = $this->preprocess($sourcePath);

        try {
            $rawText = $this->runOcr($processedPath, $binary);
        } finally {
            // Always clean up the temporary preprocessed image
            if (file_exists($processedPath) && $processedPath !== $sourcePath) {
                @unlink($processedPath);
            }
        }

        Log::debug('OCR KTP raw text', ['text' => $rawText]);

        $data = $this->parse($rawText);
        $data['_raw'] = $rawText;

        return $data;
    }

    /**
     * Normalize an uploaded file or storage path into a local filesystem path.
     */
    protected function resolveSourcePath($image): string
    {
        if ($image instanceof UploadedFile) {
            return $image->getRealPath();
        }

        // Livewire 3 TemporaryUploadedFile extends UploadedFile, so the above covers it.
        // Fallback: assume a storage path relative to the public disk.
        if (is_string($image)) {
            $path = storage_path('app/public/' . ltrim($image, '/'));
            if (file_exists($path)) {
                return $path;
            }

            $absolute = Storage::disk('public')->path(ltrim($image, '/'));
            if (file_exists($absolute)) {
                return $absolute;
            }
        }

        return (string) $image;
    }

    /**
     * Preprocess the image to improve OCR accuracy.
     */
    protected function preprocess(string $sourcePath): string
    {
        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true) ? $extension : 'jpg';

        $processedPath = sys_get_temp_dir() . '/ktp_scan_' . uniqid() . '.' . $extension;

        $image = Image::read($sourcePath);

        // Convert to grayscale, increase contrast and sharpness.
        $image->greyscale();
        $image->contrast(20);
        $image->sharpen(10);

        // Scale up small images so Tesseract reads text better.
        $width = $image->width();
        if ($width < 1200) {
            $scale = 1200 / $width;
            $image->scale(width: 1200);
        }

        $image->save($processedPath, quality: 90);

        return $processedPath;
    }

    /**
     * Run Tesseract OCR against the preprocessed image.
     */
    protected function runOcr(string $imagePath, string $binary): string
    {
        try {
            $ocr = new TesseractOCR($imagePath);
            $ocr->executable($binary);
            $ocr->lang(...explode('+', config('app.tesseract_lang', 'ind+eng')));
            $ocr->psm(3); // Auto page segmentation with OSD

            return trim($ocr->run());
        } catch (TesseractOcrException $e) {
            Log::error('Tesseract OCR failed', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Gagal membaca teks dari KTP: ' . $e->getMessage());
        }
    }

    /**
     * Parse raw OCR text into structured KTP fields.
     *
     * @return array<string, string|null>
     */
    protected function parse(string $text): array
    {
        // Normalize line breaks and common OCR artifacts.
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/\n+/', "\n", $text);
        $text = preg_replace('/\s+/', ' ', $text);

        // Fix common OCR misreadings before regex matching.
        $text = $this->normalizeOcrArtifacts($text);

        $data = [
            'nik' => null,
            'name' => null,
            'tempat_lahir' => null,
            'tanggal_lahir' => null,
            'jenis_kelamin' => null,
            'alamat' => null,
            'rt' => null,
            'rw' => null,
            'kelurahan' => null,
            'kecamatan' => null,
            'kabupaten' => null,
            'provinsi' => null,
            'agama' => null,
            'status_perkawinan' => null,
            'pekerjaan' => null,
            'kewarganegaraan' => null,
        ];

        // NIK: 16 consecutive digits.
        if (preg_match('/\b(\d{16})\b/', $text, $m)) {
            $data['nik'] = $m[1];
        }

        // Nama: usually follows "Nama" label.
        $data['name'] = $this->extractAfterLabel($text, 'Nama');

        // Tempat/Tgl Lahir — tolerate OCR artifacts like Tg!, Tgi, Tg1, etc.
        if (preg_match('/Tempat\/T[gq][l1!iI]\s*Lahir\s*[:=;]?\s*([^\n]+)/i', $text, $m) ||
            preg_match('/Tempat\s*\/\s*Tanggal\s*Lahir\s*[:=;]?\s*([^\n]+)/i', $text, $m)) {
            $parts = explode(',', trim($m[1]), 2);
            $data['tempat_lahir'] = trim($parts[0] ?? '');
            $data['tanggal_lahir'] = isset($parts[1]) ? $this->normalizeDate(trim($parts[1])) : null;
        }

        // Jenis Kelamin
        if (preg_match('/Jenis Kelamin\s*:?\s*(LAKI-LAKI|PEREMPUAN|LAKI\s*-\s*LAKI)/i', $text, $m)) {
            $data['jenis_kelamin'] = strtoupper(preg_replace('/\s+/', '', $m[1])) === 'LAKI-LAKI' ? 'LAKI-LAKI' : 'PEREMPUAN';
        }

        // Alamat
        $data['alamat'] = $this->extractAfterLabel($text, 'Alamat');

        // RT / RW
        if (preg_match('/RT\/RW\s*:?\s*(\d{2,3})\s*\/\s*(\d{2,3})/i', $text, $m)) {
            $data['rt'] = str_pad($m[1], 3, '0', STR_PAD_LEFT);
            $data['rw'] = str_pad($m[2], 3, '0', STR_PAD_LEFT);
        }

        // Kelurahan / Desa
        $data['kelurahan'] = $this->extractAfterLabel($text, 'Kel/Desa', 'Desa');

        // Kecamatan
        $data['kecamatan'] = $this->extractAfterLabel($text, 'Kecamatan');

        // Kabupaten / Kota
        if (preg_match('/Kabupaten\/Kota\s*:?\s*([^\n]+)/i', $text, $m)) {
            $data['kabupaten'] = trim($m[1]);
        }

        // Provinsi
        $data['provinsi'] = $this->extractAfterLabel($text, 'Provinsi');

        // Agama
        if (preg_match('/Agama\s*:?\s*(ISLAM|KRISTEN|KATOLIK|HINDU|BUDHA|BUDDHA|KONGHUCHU)/i', $text, $m)) {
            $data['agama'] = strtoupper($m[1]);
        }

        // Status Perkawinan
        if (preg_match('/Status Perkawinan\s*:?\s*(BELUM KAWIN|KAWIN|CERAI HIDUP|CERAI MATI)/i', $text, $m)) {
            $data['status_perkawinan'] = strtoupper($m[1]);
        }

        // Pekerjaan
        $data['pekerjaan'] = $this->extractAfterLabel($text, 'Pekerjaan');

        // Kewarganegaraan
        if (preg_match('/Kewarganegaraan\s*:?\s*(WNI|WNA)/i', $text, $m)) {
            $data['kewarganegaraan'] = strtoupper($m[1]);
        }

        return $data;
    }

    /**
     * Normalize common OCR misreadings in Indonesian KTP text.
     */
    protected function normalizeOcrArtifacts(string $text): string
    {
        $replacements = [
            // Tempat/Tgl Lahir variations
            '/Tempat\s*\/\s*T[gq][l1!iI]\s*Lahir/i' => 'Tempat/Tgl Lahir',
            '/Tempat\s*\/\s*Tanggal\s*Lahir/i' => 'Tempat/Tgl Lahir',

            // Common label fixes
            '/Jenis\s+Kelamin/i' => 'Jenis Kelamin',
            '/Gol\.?\s*Darah/i' => 'Gol Darah',
            '/Status\s+Perkawinan/i' => 'Status Perkawinan',
            '/Kewarganegaraan/i' => 'Kewarganegaraan',
            '/Pekerjaan/i' => 'Pekerjaan',
            '/Kabupaten\s*\/\s*Kota/i' => 'Kabupaten/Kota',
            '/Kel\/Desa/i' => 'Kel/Desa',
            '/Kecamatan/i' => 'Kecamatan',
            '/Provinsi/i' => 'Provinsi',
            '/Agama/i' => 'Agama',
            '/Alamat/i' => 'Alamat',
            '/RT\/RW/i' => 'RT/RW',
            '/Nama/i' => 'Nama',
            '/NIK/i' => 'NIK',

            // Separator variations
            '/\s*=\s*\?\s*/' => ' ',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }

    /**
     * Extract value after a label from OCR text.
     */
    protected function extractAfterLabel(string $text, string $label, ?string $fallbackLabel = null): ?string
    {
        $labels = [$label];
        if ($fallbackLabel) {
            $labels[] = $fallbackLabel;
        }

        foreach ($labels as $lab) {
            $escaped = preg_quote($lab, '/');
            // Match label followed by optional colon/spaces until next common label or end.
            $pattern = '/\b' . $escaped . '\b\s*:?\s*([^\n]+?)(?=\b(?:NIK|Nama|Tempat|Tgl|Jenis|Alamat|RT\/RW|Kel\/Desa|Kecamatan|Kabupaten|Provinsi|Agama|Status|Pekerjaan|Kewarganegaraan|Berlaku)\b|$)/i';

            if (preg_match($pattern, $text, $m)) {
                $value = trim($m[1]);
                // Clean trailing label fragments.
                $value = preg_replace('/\s*[:\-]$/', '', $value);
                return $value ?: null;
            }
        }

        return null;
    }

    /**
     * Normalize Indonesian date formats to YYYY-MM-DD.
     */
    protected function normalizeDate(string $date): ?string
    {
        $date = trim($date);

        // Common Indonesian format: 17-08-1990 or 17/08/1990
        if (preg_match('/(\d{1,2})[\-\/](\d{1,2})[\-\/](\d{4})/', $date, $m)) {
            [$d, $mo, $y] = [(int) $m[1], (int) $m[2], (int) $m[3]];
            return sprintf('%04d-%02d-%02d', $y, $mo, $d);
        }

        // Format with month name: 17 Agustus 1990
        $months = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
        ];

        if (preg_match('/(\d{1,2})\s+([a-z]+)\s+(\d{4})/i', $date, $m)) {
            $monthKey = strtolower($m[2]);
            if (isset($months[$monthKey])) {
                return sprintf('%04d-%02d-%02d', (int) $m[3], $months[$monthKey], (int) $m[1]);
            }
        }

        return $date ?: null;
    }

    /**
     * Return the configured Tesseract binary path.
     */
    protected function getBinaryPath(): string
    {
        $path = config('app.tesseract_path');

        // Strip quotes that might be present in the .env value.
        $path = trim($path, '"\'');

        return $path;
    }
}
