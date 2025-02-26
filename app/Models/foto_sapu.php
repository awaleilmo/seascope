<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foto_sapu extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_sapu_id',
        'foto',
        'urutan'
    ];
}
