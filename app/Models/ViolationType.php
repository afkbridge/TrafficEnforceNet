<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | Existing relationship
    |--------------------------------------------------------------------------
    | Keep this so the current system continues to work.
    */
    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Multiple violation types relationship
    |--------------------------------------------------------------------------
    */
    public function violationRecords()
    {
        return $this->belongsToMany(
            Violation::class,
            'violation_violation_type',
            'violation_type_id',
            'violation_id'
        )->withTimestamps();
    }
}