<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('subscription_plans')->insert([
            [
                'name' => 'Gratis (Free Trial)',
                'price' => 0,
                'duration_days' => 7,
                'features' => json_encode(['Akses penuh', 'Data tidak terbatas', 'Masa aktif 7 hari']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Berbayar (Premium)',
                'price' => 50000,
                'duration_days' => 30,
                'features' => json_encode(['Akses penuh', 'Data tidak terbatas', 'Dukungan prioritas']),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('subscription_plans')->whereIn('name', ['Gratis (Free Trial)', 'Berbayar (Premium)'])->delete();
    }
};
