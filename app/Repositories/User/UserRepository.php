<?php
namespace App\Repositories\User;

use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    public function all() {}
    public function paginate($limit, $q, $role, $department) {
        $users = User::with('role', 'userInfo.identityCard', 'patient.patientInfo', 'patient.identityCard')
            ->when($q, function ($query, $q) {
                $query->where('email', 'LIKE', "%{$q}%")
                      ->orWhereHas('userInfo', function ($query) use ($q) {
                          $query->where('fullname', 'LIKE', "%{$q}%");
                        })
                      ->orWhereHas('patient.patientInfo', function ($query) use ($q) {
                        $query->where('fullname', 'LIKE', "%{$q}%");
                        });
            });
        if ($role) {
            $users->whereHas('role', function ($query) use ($role) {
                $query->where('name', $role);
            });
        }

        if ($department) {
            $users->whereHas('userInfo', function ($query) use ($department) {
                $query->where('department_id', $department);
              });
        }
    
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
    public function getUserCheckDepartment($limit) {
        $users = User::with(['userInfo:id,user_id,department_id,avatar,fullname'])
            ->select('id', 'email')
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'patient');
            });
    
        return $limit ? $users->paginate($limit) : $users->get();
    }
    public function getBySpecialtyId($specialty_id) {
        $users = User::with('doctor.specialty', 'userInfo')
        ->whereHas('role', function ($query) {
            $query->where('name', 'doctor');
        })->whereHas('doctor', function ($query) use ($specialty_id) {
            $query->where('specialty_id', $specialty_id);
        })->get();

        return $users;
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