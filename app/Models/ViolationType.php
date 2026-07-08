<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * One violation type can have many violations.
     */
    public function violations()
    {
        return $this->hasMany(Violation::class);
    }
}