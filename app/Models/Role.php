<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TypeRole;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $fillable = [
        'name',
        'description',
        'type_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function type() {
        return $this->belongsTo(TypeRole::class, 'type_id');
    }
}
