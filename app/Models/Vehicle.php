<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'driver_id',
        'plate_number',
        'vehicle_type',
        'brand',
        'model',
        'color',
        'engine_number',
        'chassis_number',
    ];

    /**
     * Vehicle belongs to one driver.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Vehicle can have many violations.
     */
    public function violations()
    {
        return $this->hasMany(Violation::class);
    }
}