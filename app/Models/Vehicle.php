<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'driver_id',
        'plate_number',
        'has_no_plate',
        'vehicle_type',
        'region_number',
        'owner_name',
    ];

    protected $casts = [
        'has_no_plate' => 'boolean',
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