<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personil_sapu extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_sapu_id',
        'name',
        'jml',
        'sat',
        'index'
    ];
}
