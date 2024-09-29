<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserInfo;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'description',
        'manager_id',
    ];

    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users() {
        return $this->hasMany(UserInfo::class, 'department_id');
    }
}
