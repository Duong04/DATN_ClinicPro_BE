<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryPackage extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "category_packages";
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function packages()
    {
        return $this->hasMany(Package::class, 'category_id');
    }
}
