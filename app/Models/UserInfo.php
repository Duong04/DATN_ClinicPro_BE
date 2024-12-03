<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IdentityCard;
use App\Models\User;

class UserInfo extends Model
{
    use HasFactory, UsesUuid;

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

    public function identityCard() {
        return $this->belongsTo(IdentityCard::class, 'identity_card_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
