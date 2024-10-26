<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory, UsesUuid;
    protected $table = 'medications';
    protected $fillable = [
        'name',
    ];
}
