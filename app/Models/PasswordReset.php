<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'password_resets';

    protected $fillable = [
        'user_id', 
        'otp',
        'expires_at'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
