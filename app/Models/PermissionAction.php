<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionAction extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'permission_actions';

    protected $fillable = [
        'action_id',
        'permission_id'
    ];
}
