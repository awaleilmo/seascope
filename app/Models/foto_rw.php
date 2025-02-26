<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foto_rw extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_rw_id',
        'foto',
        'urutan'
    ];
}
