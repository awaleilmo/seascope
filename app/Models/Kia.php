<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class Kia extends Model
{
    use HasFactory;

    protected $appends = [
        'tanggal_fotmated',
        'kronologis_short',
        'saksi_short',
        'barang_bukti_short',
        'melanggar_pasal_short',
        'kerugian_formated'
    ];

    private $limit = 200;
    public function getTanggalFotmatedAttribute()
    {
        Carbon::setLocale('id');
        $date = Carbon::parse($this->tanggal_lp);

        return $date->translatedFormat('d F Y');
    }

    public function getKronologisShortAttribute()
    {
        return Str::limit($this->kronologis, $this->limit, ' (...)');
    }
    public function getSaksiShortAttribute()
    {
        return Str::limit($this->saksi, $this->limit, ' (...)');
    }
    public function getBarangBuktiShortAttribute()
    {
        return Str::limit($this->barang_bukti, $this->limit, ' (...)');
    }
    public function getMelanggarPasalShortAttribute()
    {
        return Str::limit($this->melanggar_pasal, $this->limit, ' (...)');
    }
    public function getKerugianFormatedAttribute()
    {
        $nilai = $this->kerugian ? $this->kerugian : 0;

        return Number::currency($nilai, 'IDR', 'id');
    }
}
