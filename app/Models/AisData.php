<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AisData extends Model
{
    use HasFactory;

    protected $fillable = [
        'msg_type',
        'repeat_indicator',
        'mmsi',
        'nav_status',
        'rot_over_range',
        'rot',
        'sog',
        'position_accuracy',
        'x',
        'y',
        'cog',
        'true_heading',
        'timestamp',
        'special_manoeuvre',
        'raim',
        'sync_state',
        'slot_increment',
        'slots_to_allocate',
        'keep_flag',
        'altitude',
        'channel',
    ];

    public function staticInfo(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AisStatic::class, 'mmsi', 'mmsi');
    }
}
