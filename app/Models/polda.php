<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class polda extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kota',
        'alamat',
        'lokasi_id'
    ];

    public function sambang(): HasMany
    {
        return $this->hasMany(report_sambang::class, 'polda_id', 'id');
    }
    public function klinik(): HasMany
    {
        return $this->hasMany(report_klinik::class, 'polda_id', 'id');
    }
    public function perpustakaan(): HasMany
    {
        return $this->hasMany(report_perpustakaan::class, 'polda_id', 'id');
    }
    public function rw(): HasMany
    {
        return $this->hasMany(report_rw::class, 'polda_id', 'id');
    }
    public function sapu(): HasMany
    {
        return $this->hasMany(report_sapu::class, 'polda_id', 'id');
    }
    public function lokasi(): HasOne
    {
        return $this->hasOne(lokasi::class, 'id', 'lokasi_id');
    }
}
