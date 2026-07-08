<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationImage extends Model
{
    protected $fillable = [
        'violation_id',
        'image_path',
    ];

    /**
     * The violation this image belongs to.
     */
    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }
}