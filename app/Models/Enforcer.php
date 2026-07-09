<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Enforcer extends Model
{
    protected $fillable = [
        'badge_number',
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'email',
        'position',
        'employment_status',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
