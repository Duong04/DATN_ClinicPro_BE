<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentityCard extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'identity_cards';

    protected $fillable = [
        'type_name',
        'identity_card_number'
    ];

    public $timestamps = false;

}
