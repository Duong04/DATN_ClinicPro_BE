<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PermissionAction;
use App\Models\Action;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'description',
    ];

    public function permissionActions() {
        return $this->hasMany(PermissionAction::class, 'permission_id');
    }

    public function actions()
    {
        return $this->belongsToMany(Action::class, 'role_permissions', 'permission_id', 'action_id')->withPivot('role_id', 'permission_id','action_id');
    }
}
