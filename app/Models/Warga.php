<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['rt_id', 'nik', 'no_kk', 'name', 'status_domisili', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'foto_path', 'ktp_path'])]
class Warga extends Model
{
    public function rt() { return $this->belongsTo(Rt::class); }
    public function surats() { return $this->hasMany(Surat::class); }
    public function kasTransactions() { return $this->hasMany(KasTransaction::class); }
    public function bantuans() { return $this->belongsToMany(Bantuan::class, 'bantuan_warga')->withTimestamps(); }

    protected static function booted()
    {
        static::deleting(function ($warga) {
            if ($warga->foto_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($warga->foto_path);
            }
            if ($warga->ktp_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($warga->ktp_path);
            }
        });
    }
}
