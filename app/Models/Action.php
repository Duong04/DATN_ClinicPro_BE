<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PermissionAction;

class Action extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'actions';

    protected $fillable = [
        'name',
        'value'
    ];

    public function permissionActions() {
        return $this->hasMany(PermissionAction::class, 'action_id');
    }
}
