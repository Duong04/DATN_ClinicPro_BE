<?php
namespace App\Repositories\Department;

use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Models\Department;
class DepartmentRepository implements DepartmentRepositoryInterface {
    public function all() {
        return Department::all();
    }
    public function paginate($limit, $q) {
        $departments = Department::with(['manager.userInfo' => function ($query) {
                $query->select('id', 'user_id', 'fullname', 'address', 'avatar', 'gender', 'dob', 'phone_number');
            },
            'users.user.doctor.specialty' => function ($query) {
                $query->select('id', 'name', 'description');
            },
            'users.user.doctor' => function ($query) {
                $query->select('id', 'specialty_id', 'user_id');
            },
            'users.user' => function ($query) {
                $query->select('id', 'status', 'email');
            }])->withCount('users');
        if ($q) {
            $departments->where('name', 'like', "%{$q}%")
            ->orWhereHas('manager', function ($query) use ($q) {
                $query->where('email', 'like', "%{$q}%");
            })
            ->orWhereHas('manager.userInfo', function ($query) use ($q) {
                $query->where('fullname', 'like', "%{$q}%");
            });;
        }

        $departments->orderByDesc('created_at');
        return $limit ? $departments->paginate($limit) : $departments->get();
    }
    public function find($id) {
        return Department::with([
            'manager.userInfo' => function($query) {
                $query->select('id', 'user_id', 'fullname', 'address', 'avatar', 'gender', 'dob', 'phone_number');
            },
            'users.user.doctor.specialty' => function ($query) {
                $query->select('id', 'name', 'description');
            },
            'users.user.doctor' => function ($query) {
                $query->select('id', 'specialty_id', 'user_id');
            },
            'users.user' => function ($query) {
                $query->select('id', 'status', 'email');
            }
        ])
        ->withCount('users')
        ->find($id);
    }
    public function create(array $data) {
        return Department::create($data);
    }
    public function update($id, array $data) {
        $department = Department::find($id);
        return $department->update($data);
    }
    public function delete($id) {
        $department = Department::find($id);
        return $department->delete();
    }
}