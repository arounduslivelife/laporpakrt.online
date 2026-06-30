<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OcrStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocr:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Tesseract OCR installation status';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $binary = config('app.tesseract_path');

        $this->info('Tesseract OCR Status Check');
        $this->info('==========================');
        $this->line('Configured binary: ' . $binary);
        $this->line('Configured language: ' . config('app.tesseract_lang'));
        $this->newLine();

        if (! file_exists($binary)) {
            $this->error('Tesseract binary NOT FOUND at: ' . $binary);
            $this->newLine();
            $this->line('Installation guide:');
            $this->line('  Windows: https://github.com/UB-Mannheim/tesseract/wiki');
            $this->line('  Ubuntu/Debian: sudo apt install tesseract-ocr tesseract-ocr-ind');
            $this->line('  CentOS: sudo yum install tesseract tesseract-langpack-ind');
            $this->newLine();
            $this->line('Then update TESSERACT_PATH in your .env file if needed.');

            return self::FAILURE;
        }

        $this->info('Tesseract binary FOUND at: ' . $binary);

        // Try to get version
        $output = shell_exec('"' . $binary . '" --version 2>&1');

        if ($output) {
            $this->info('Version info:');
            foreach (explode("\n", trim($output)) as $line) {
                $this->line('  ' . $line);
            }
        }

        // Check language packs
        $langOutput = shell_exec('"' . $binary . '" --list-langs 2>&1');
        $langs = [];

        if ($langOutput) {
            $lines = explode("\n", trim($langOutput));
            array_shift($lines); // Remove "List of available languages" header
            $langs = array_filter(array_map('trim', $lines));
        }

        $required = explode('+', config('app.tesseract_lang', 'ind+eng'));
        $missing = array_diff($required, $langs);

        $this->newLine();
        $this->info('Available languages: ' . implode(', ', $langs ?: ['(none detected)']));

        if (! empty($missing)) {
            $this->warn('Missing language packs: ' . implode(', ', $missing));
            $this->line('Install with:');
            $this->line('  Ubuntu/Debian: sudo apt install tesseract-ocr-' . implode(' tesseract-ocr-', $missing));
            $this->line('  Windows: re-run installer and select language packs');

            return self::FAILURE;
        }

        $this->info('All required language packs are available.');
        $this->info('OCR KTP feature is ready to use.');

        return self::SUCCESS;
    }
}
