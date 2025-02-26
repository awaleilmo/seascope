<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AisStatic extends Model
{
    use HasFactory;

    protected $fillable = ['mmsi', 'shipname', 'ship_type', 'to_bow', 'to_stern', 'to_port', 'to_starboard'];
}
