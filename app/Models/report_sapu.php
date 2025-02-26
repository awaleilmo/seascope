<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class report_sapu extends Model
{
    use HasFactory;

    protected $fillable = [
        'polda_id',
        'personil_ket',
        'personil_jml',
        'personil_sat',
        'lokasi',
        'sampah_organik_jml',
        'sampah_organik_ket',
        'sampah_anorganik_jml',
        'sampah_anorganik_ket',
        'keterangan',
        'foto',
        'tanggal',
        'waktu'
    ];

    public function Foto(): HasMany
    {
        return $this->hasMany(foto_sapu::class, 'report_sapu_id', 'id');
    }
    public function Polda(): HasOne
    {
        return $this->hasOne(polda::class, 'id', 'polda_id');
    }
}
