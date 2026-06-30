<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    public function parent()   { return $this->belongsTo(Region::class, 'parent_code', 'code'); }
    public function children() { return $this->hasMany(Region::class, 'parent_code', 'code'); }

    public function getDistrictAttribute()
    {
        return $this->type === 'VILLAGE' ? $this->parent : null;
    }

    public function getRegencyAttribute()
    {
        return $this->district ? $this->district->parent : null;
    }

    public function getProvinceAttribute()
    {
        return $this->regency ? $this->regency->parent : null;
    }
}
