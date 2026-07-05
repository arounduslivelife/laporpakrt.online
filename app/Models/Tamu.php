<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'rt_id', 'name', 'nik', 'alamat', 'tujuan', 'lama_kunjungan_hari',
    'foto_wajah', 'foto_ktp', 'lat', 'lng', 'created_by',
])]
class Tamu extends Model
{
    public function rt()       { return $this->belongsTo(Rt::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }

    protected static function booted()
    {
        static::deleting(function ($tamu) {
            if ($tamu->foto_wajah) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tamu->foto_wajah);
            }
            if ($tamu->foto_ktp) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tamu->foto_ktp);
            }
        });
    }
}
