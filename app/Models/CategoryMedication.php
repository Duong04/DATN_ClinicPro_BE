<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMedication extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "category_medication";

    protected $fillable = [
        'name'
    ];
}
