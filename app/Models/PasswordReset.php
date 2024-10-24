<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';

    protected $fillable = [
        'user_id', 
        'otp',
        'expries_at'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
