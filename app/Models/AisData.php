<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AisData extends Model
{
    use HasFactory;

    protected $fillable = [
        'msg_type', 'repeat', 'mmsi', 'status', 'turn', 'speed', 'accuracy', 'lon', 'lat', 'course', 'heading', 'second', 'maneuver', 'spare_1', 'raim', 'radio'
    ];

    public function staticInfo(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AisStatic::class, 'mmsi', 'mmsi');
    }
}
