<?php
namespace App\Repositories\User;

use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    public function all() {}
    public function paginate($limit, $q) {
        $users = User::with('role', 'userInfo.identityCard', 'patient.patientInfo', 'patient.identityCard')
            ->when($q, function ($query, $q) {
                $query->where('email', 'LIKE', "%{$q}%")
                      ->orWhereHas('userInfo', function ($query) use ($q) {
                          $query->where('fullname', 'LIKE', "%{$q}%");
                        })
                      ->orWhereHas('patient.patientInfo', function ($query) use ($q) {
                        $query->where('fullname', 'LIKE', "%{$q}%");
                        })
                      ->orWhereHas('role', function ($query) use ($q) {
                          $query->where('name', 'LIKE', "%{$q}%");
                        });
            });
    
        return $limit ? $users->paginate($limit) : $users->get();
    }
    public function getByRoleId($role_id, $limit, $q) {
        $users = User::with('role', 'userInfo.identityCard', 'patient.patientInfo', 'patient.identityCard')
            ->where('role_id', $role_id)
            ->when($q, function ($query, $q) {
                $query->where('email', 'LIKE', "%{$q}%")
                      ->orWhereHas('userInfo', function ($query) use ($q) {
                          $query->where('fullname', 'LIKE', "%{$q}%");
                        })
                      ->orWhereHas('patient.patientInfo', function ($query) use ($q) {
                        $query->where('fullname', 'LIKE', "%{$q}%");
                        })
                      ->orWhereHas('role', function ($query) use ($q) {
                          $query->where('name', 'LIKE', "%{$q}%");
                        });
            });
        $users->orderByDesc('created_at');

        return $limit ? $users->paginate($limit) : $users->get();
    }
    public function find($id, array $relation) {
        return user::with($relation)->find($id);
    }
    public function create(array $data) {
        return User::create($data);
    }
    public function update($id, array $data) {
        $user = User::find($id);
        return $user->update($data);
    }
    public function delete($id) {}
}