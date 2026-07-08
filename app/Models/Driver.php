<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'license_number',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'birth_date',
        'contact_number',
        'license_type',
        'license_expiration',
    ];

    /**
     * A driver can have many violations.
     */
    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    /**
     * A driver can own many vehicles.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Get the driver's full name.
     */
    public function getFullNameAttribute()
    {
        return trim(
            $this->first_name . ' ' .
            ($this->middle_name ? $this->middle_name . ' ' : '') .
            $this->last_name
        );
    }
}