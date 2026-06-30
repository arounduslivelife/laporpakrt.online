<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // We will fetch from edwardsamuel/Wilayah-Administratif-Indonesia
        $baseUrl = 'https://raw.githubusercontent.com/edwardsamuel/Wilayah-Administratif-Indonesia/master/csv/';

        // Truncate existing data to avoid duplicates or conflicts
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Region::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Downloading and inserting Provinces...');
        $this->insertCsv($baseUrl . 'provinces.csv', 'PROVINCE', function ($row) {
            return [
                'code' => $row[0],
                'name' => $row[1],
                'type' => 'PROVINCE',
                'parent_code' => null,
            ];
        });

        $this->command->info('Downloading and inserting Regencies...');
        $this->insertCsv($baseUrl . 'regencies.csv', 'REGENCY', function ($row) {
            return [
                'code' => $row[0],
                'name' => $row[2],
                'type' => 'REGENCY',
                'parent_code' => $row[1],
            ];
        });

        $this->command->info('Downloading and inserting Districts...');
        $this->insertCsv($baseUrl . 'districts.csv', 'DISTRICT', function ($row) {
            return [
                'code' => $row[0],
                'name' => $row[2],
                'type' => 'DISTRICT',
                'parent_code' => $row[1],
            ];
        });

        $this->command->info('Downloading and inserting Villages... (This might take a while)');
        $this->insertCsv($baseUrl . 'villages.csv', 'VILLAGE', function ($row) {
            return [
                'code' => $row[0],
                'name' => $row[2],
                'type' => 'VILLAGE',
                'parent_code' => $row[1],
            ];
        });

        $this->command->info('All regional data has been seeded successfully!');
    }

    private function insertCsv($url, $type, $mapper)
    {
        $context = stream_context_create([
            'http' => [
                'header' => "User-Agent: LaporPakRT-App\r\n"
            ]
        ]);
        
        $handle = fopen($url, 'r', false, $context);
        
        if ($handle === false) {
            $this->command->error("Failed to open URL: $url");
            return;
        }

        $chunk = [];
        $chunkSize = 1000;
        $now = now();

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            // Check if valid row
            if (count($data) < 2) continue;

            $mapped = $mapper($data);
            if ($mapped) {
                $mapped['created_at'] = $now;
                $mapped['updated_at'] = $now;
                $chunk[] = $mapped;
            }

            if (count($chunk) >= $chunkSize) {
                Region::insertOrIgnore($chunk);
                $chunk = [];
            }
        }

        if (count($chunk) > 0) {
            Region::insertOrIgnore($chunk);
        }

        fclose($handle);
    }
}
