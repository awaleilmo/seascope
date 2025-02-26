<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class report_perpustakaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'polda_id',
        'personil_jml',
        'personil_sat',
        'lokasi',
        'jml_peserta',
        'asal_peserta',
        'hasil',
        'keterangan',
        'foto',
        'tanggal',
        'waktu',
    ];
    public function Foto(): HasMany
    {
        return $this->hasMany(foto_perpustakaan::class, 'report_perpustakaan_id', 'id');
    }
    public function Polda(): HasOne
    {
        return $this->hasOne(polda::class, 'id', 'polda_id');
    }
}
