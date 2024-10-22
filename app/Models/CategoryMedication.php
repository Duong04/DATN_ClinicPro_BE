<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMedication extends Model
{
    use HasFactory;
    protected $table = "category_medication";

    protected $fillable = [
        'name'
    ];
}
