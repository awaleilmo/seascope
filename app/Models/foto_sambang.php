<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foto_sambang extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_sambang_id',
        'foto',
        'urutan'
    ];
}
