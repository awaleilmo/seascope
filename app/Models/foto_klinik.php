<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foto_klinik extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_klinik_id',
        'foto',
        'urutan'
    ];
}
