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
        'status',
    ];

    /**
     * Driver who committed the violation.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function index()
    {
    $violations = Violation::with('driver')->get();

    return view('admin.violations.index', compact('violations'));
    }   
    
    /**
     * Vehicle involved.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Violation category.
     */
    public function violationType()
    {
        return $this->belongsTo(ViolationType::class);
    }

    /**
     * Enforcer who encoded the violation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Photos attached to the violation.
     */
    public function images()
    {
        return $this->hasMany(ViolationImage::class);
    }
}