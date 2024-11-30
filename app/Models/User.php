<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Role;
use App\Models\UserInfo;
use App\Models\Patient;
use App\Models\Doctor;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role_id',
        'token',
        'otp'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'email_verified_at',
        'token',
        'otp'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->contains('name', $permissionName);
    }

    public function hasAction($permissionName, $actionName, $role_id)
    {
        $permission = $this->permissions()->where('name', $permissionName)->first();

        if (!$permission) {
            return [];
        }

        $filteredActions = $permission->actions->filter(function ($action) use ($role_id, $permission) {
            return $action->pivot->role_id == $role_id && $action->pivot->permission_id == $permission->id;
        })->values();

        $permissionNew = [
            'actions' => $filteredActions,
        ];

        if ($permissionNew) {
            return $permissionNew['actions']->contains('value', $actionName);
        }
        return false;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
