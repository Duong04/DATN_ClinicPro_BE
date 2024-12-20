<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Action;
use App\Models\Permission;
use App\Models\Role;

class RolePermission extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'role_permissions';

    protected $fillable = [
        'role_id',
        'permission_id',
        'action_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }
}
