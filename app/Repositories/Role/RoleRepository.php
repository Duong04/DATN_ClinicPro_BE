<?php
namespace App\Repositories\Role;

use App\Repositories\Role\RoleRepositoryInterface;
use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface {
    public function all() {
        
    }
    public function paginate($limit, $q) {
        $roles = Role::with([
        'users.userInfo' => function ($query) {
            $query->select('id', 'user_id', 'fullname', 'avatar');
        },
        'users.patient.patientInfo' => function ($query) {
            $query->select('patient_id', 'fullname', 'avatar');
        }])->withCount('users');
        if ($q) {
            $roles->where('name', 'like', "%{$q}%");
        }

        $roles->orderByDesc('created_at');

        return $limit ? $roles->paginate($limit) : $roles->get();
    }
    public function find($id) {
        return Role::with([
        'permissions.actions', 
        'users.userInfo' => function ($query) {
            $query->select('id', 'user_id', 'fullname', 'avatar');
        },
        'users.patient.patientInfo' => function ($query) {
            $query->select('patient_id', 'fullname', 'avatar');
        }])->withCount('users')->find($id);
    }
    public function create(array $data) {
        return Role::create($data);
    }
    public function update($id, array $data) {
        $role = Role::find($id);
        return $role->update($data);
    }
    public function delete($id) {
        $role = Role::find($id);
        return $role->delete();
    }
}