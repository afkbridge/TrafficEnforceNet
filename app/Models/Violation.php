<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'ticket_number',
        'driver_id',
        'vehicle_id',
        'violation_type_id',
        'user_id',
        'violation_date',
        'violation_time',
        'location',
        'latitude',
        'longitude',
        'remarks',
        'ticket_image',
        'status',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Existing single violation relationship
    |--------------------------------------------------------------------------
    | Keep this for compatibility with the current system.
    */
    public function violationType()
    {
        return $this->belongsTo(ViolationType::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Multiple violation types
    |--------------------------------------------------------------------------
    */
    public function violationTypes()
    {
        return $this->belongsToMany(
            ViolationType::class,
            'violation_violation_type',
            'violation_id',
            'violation_type_id'
        )->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ViolationImage::class);
    }
}