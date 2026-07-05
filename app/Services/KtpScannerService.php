<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class KtpScannerService
{
    /**
     * Scan a KTP image and extract identity fields using Google Gemini Vision AI.
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

        // Get API Key from database settings
        $setting = Setting::where('key', 'gemini_api_key')->first();
        $apiKey = $setting ? $setting->value : null;

        if (empty($apiKey)) {
            throw new \RuntimeException('Google Gemini API Key belum dikonfigurasi. Silakan atur di menu Pengaturan Sistem.');
        }

        // Convert image to base64
        $imageData = base64_encode(file_get_contents($sourcePath));
        $mimeType = mime_content_type($sourcePath) ?: 'image/jpeg';

        try {
            $jsonResponse = $this->callGeminiApi($imageData, $mimeType, $apiKey);
            $data = $this->parseJsonResponse($jsonResponse);
            $data['_raw'] = $jsonResponse; // Keep raw for debugging

            return $data;
        } catch (\Exception $e) {
            Log::error('Gemini OCR failed', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Gagal membaca teks dari KTP (Gemini API): ' . $e->getMessage());
        }
    }

    /**
     * Call Google Gemini API
     */
    protected function callGeminiApi(string $base64Image, string $mimeType, string $apiKey): string
    {
        // Using gemini-2.5-flash which is extremely fast and capable of vision tasks
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

        $prompt = "Anda adalah sistem OCR KTP Indonesia yang sangat akurat. 
Tugas Anda adalah mengekstrak data identitas dari gambar KTP ini.
Abaikan latar belakang seperti meja, tangan, dll.
Kembalikan respon murni HANYA berupa JSON (tanpa markdown backticks, tanpa kata-kata lain).
Struktur JSON yang WAJIB digunakan:
{
  \"nik\": \"string (16 digit angka)\",
  \"name\": \"string (nama lengkap)\",
  \"tempat_lahir\": \"string\",
  \"tanggal_lahir\": \"string (format YYYY-MM-DD)\",
  \"jenis_kelamin\": \"string (LAKI-LAKI atau PEREMPUAN)\",
  \"alamat\": \"string\",
  \"rt\": \"string (3 digit angka)\",
  \"rw\": \"string (3 digit angka)\",
  \"kelurahan\": \"string\",
  \"kecamatan\": \"string\",
  \"kabupaten\": \"string\",
  \"provinsi\": \"string\",
  \"agama\": \"string\",
  \"status_perkawinan\": \"string\",
  \"pekerjaan\": \"string\",
  \"kewarganegaraan\": \"string (WNI atau WNA)\"
}";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $base64Image,
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'temperature' => 0.1 // Low temperature for high accuracy/determinism
            ]
        ];

        $response = Http::timeout(30)->post($url, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('API Error: ' . $response->body());
        }

        $responseData = $response->json();
        
        // Extract text response from Gemini structure
        $text = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
        
        return trim($text);
    }

    /**
     * Parse JSON response into the expected array structure
     */
    protected function parseJsonResponse(string $jsonString): array
    {
        // Clean markdown backticks if Gemini accidentally includes them
        $jsonString = str_replace(['```json', '```'], '', $jsonString);
        $jsonString = trim($jsonString);

        $parsed = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Failed to parse Gemini JSON', ['raw' => $jsonString, 'error' => json_last_error_msg()]);
            // Provide empty fallback structure
            $parsed = [];
        }

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

        // Merge parsed data safely
        if (is_array($parsed)) {
            foreach ($data as $key => $value) {
                if (isset($parsed[$key])) {
                    $val = trim((string) $parsed[$key]);
                    // Only assign if it's not empty, not "null", and not placeholder texts
                    if ($val !== '' && strtolower($val) !== 'null' && strtolower($val) !== 'tidak terbaca' && strtolower($val) !== 'none') {
                        $data[$key] = strtoupper($val);
                    }
                }
            }
            
            // Re-format dates if needed
            if (!empty($data['tanggal_lahir'])) {
                $data['tanggal_lahir'] = str_replace('/', '-', $data['tanggal_lahir']);
            }
        }

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
}
