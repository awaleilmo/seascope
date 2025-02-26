<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class giat extends Model
{
    use HasFactory;

    protected $fillable = [
        'lokasi_id',
        'sambang',
        'rw',
        'perpustakaan',
        'klinik',
        'sampah',
        'keterangan',
        'tahun'
    ];
}
