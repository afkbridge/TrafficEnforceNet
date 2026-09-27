<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViolationOtherType extends Model
{
    protected $fillable = [
        'violation_id',
        'name',
    ];

    public function violation(): BelongsTo
    {
        return $this->belongsTo(Violation::class);
    }
}