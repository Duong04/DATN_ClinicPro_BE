<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'user_infos';
    protected $fillable = [
        'fullname',
        'address',
        'avatar',
        'phone_number',
        'gender',
        'dob',
        'identity_card_id',
        'department_id',
        'user_id',
    ];
}
