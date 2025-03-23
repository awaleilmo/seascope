<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AisStatic extends Model
{
    use HasFactory;

    protected $fillable = [
        'msg_type',
        'repeat_indicator',
        'mmsi',
        'message_id',
        'fragment_count',
        'ais_version',
        'imo_number',
        'call_sign',
        'ship_name',
        'ship_type',
        'vendor_id',
        'dimension_to_bow',
        'dimension_to_stern',
        'dimension_to_port',
        'dimension_to_starboard',
        'fix_type',
        'eta_month',
        'eta_day',
        'eta_hour',
        'eta_minute',
        'draught',
        'destination',
        'dte',
    ];
}
