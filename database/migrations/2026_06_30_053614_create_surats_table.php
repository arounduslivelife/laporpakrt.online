<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rt_id')->index();
            $table->unsignedBigInteger('warga_id')->index();
            $table->string('nomor_surat')->unique();
            $table->string('jenis'); // pengantar_kk, keterangan_usaha, dll
            $table->text('keperluan');
            $table->enum('status', ['draft', 'diajukan', 'ditandatangani'])->default('draft');
            $table->string('file_path')->nullable();      // PDF hasil generate
            $table->string('qrcode_token', 32)->unique();  // untuk verifikasi
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
