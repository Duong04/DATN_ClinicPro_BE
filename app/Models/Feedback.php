<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'feedbacks';
    protected $fillable = [
        'rating',
        'content',
        'user_id',
        'package_id'
    ];
}
