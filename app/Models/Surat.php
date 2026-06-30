<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = ['rt_id', 'warga_id', 'nomor_surat', 'jenis', 'keperluan', 'status', 'file_path', 'qrcode_token', 'signed_at'];

    public function rt() { return $this->belongsTo(Rt::class); }
    public function warga() { return $this->belongsTo(Warga::class); }
}
