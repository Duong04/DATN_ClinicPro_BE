<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'examination_packages';
    protected $fillable = [
        'name',
        'description',
        'content',
        'image',
        'slug',
        "category_id"
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryPackage::class, 'category_id');
    }
}
