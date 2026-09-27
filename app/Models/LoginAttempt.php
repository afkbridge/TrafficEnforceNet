<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'latitude',
        'longitude',
        'device_token',
        'user_agent',
        'status',
        'risk_score',
        'reason',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'risk_score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}