<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foto_perpustakaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_perpustakaan_id',
        'foto',
        'urutan'
    ];
}
