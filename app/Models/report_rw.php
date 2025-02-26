<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class report_rw extends Model
{
    use HasFactory;

    protected $fillable = [
        'polda_id',
        'personil_jml',
        'personil_sat',
        'sambang',
        'pemecahaan',
        'informasi',
        'penanganan',
        'keterangan',
        'foto',
        'tanggal',
        'waktu',
    ];

    public function Foto(): HasMany
    {
        return $this->hasMany(foto_rw::class, 'report_rw_id', 'id');
    }
    public function Polda(): HasOne
    {
        return $this->hasOne(polda::class, 'id', 'polda_id');
    }
}
