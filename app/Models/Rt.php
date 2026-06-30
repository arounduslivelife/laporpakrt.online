<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['village_code', 'rw', 'rt', 'admin_id'])]
class Rt extends Model
{
    public function admin()      { return $this->belongsTo(User::class, 'admin_id'); }
    public function wargas()     { return $this->hasMany(Warga::class); }
    public function tamus()      { return $this->hasMany(Tamu::class); }
    public function village()    { return $this->belongsTo(Region::class, 'village_code', 'code'); }
    public function surats() { return $this->hasMany(Surat::class); }
    public function kasTransactions() { return $this->hasMany(KasTransaction::class); }
}
