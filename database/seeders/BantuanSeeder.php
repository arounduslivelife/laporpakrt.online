<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bantuans = [
            ['name' => 'PKH', 'description' => 'Program Keluarga Harapan'],
            ['name' => 'BPNT / Sembako', 'description' => 'Bantuan Pangan Non-Tunai / Program Sembako'],
            ['name' => 'PBI-JKN / KIS', 'description' => 'Penerima Bantuan Iuran Jaminan Kesehatan Nasional'],
            ['name' => 'PIP', 'description' => 'Program Indonesia Pintar (Pendidikan)'],
            ['name' => 'BLT', 'description' => 'Bantuan Langsung Tunai (Dana Desa, El Nino, Mitigasi)'],
            ['name' => 'Bantuan Beras', 'description' => 'Bantuan Beras 10 Kg CBP'],
        ];

        foreach ($bantuans as $bantuan) {
            \App\Models\Bantuan::create($bantuan);
        }
    }
}
