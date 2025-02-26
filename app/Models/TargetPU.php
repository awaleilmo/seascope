<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetPU extends Model
{
    use HasFactory;

    protected $fillable = ['klinik','perpustakaan','rw','sambang','sapu','tahun'];
}
