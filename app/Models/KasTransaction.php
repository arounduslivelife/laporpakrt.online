<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasTransaction extends Model
{
    protected $fillable = ['rt_id', 'tipe', 'jumlah', 'kategori', 'keterangan', 'warga_id', 'periode_bulan'];

    public function rt() { return $this->belongsTo(Rt::class); }
    public function warga() { return $this->belongsTo(Warga::class); }
}
