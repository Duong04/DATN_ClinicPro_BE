<?php
namespace App\Repositories\Department;

use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Models\Department;
class DepartmentRepository implements DepartmentRepositoryInterface {
    public function all() {
        return Department::all();
    }
    public function paginate($limit, $q) {
        $departments = Department::with('manager')->withCount('users');
        if ($q) {
            $departments->where('name', 'like', "%{$q}%");
        }

        $departments->orderByDesc('created_at');
        return $limit ? $departments->paginate($limit) : $departments->get();
    }
    public function find($id) {
        return Department::with('manager')->withCount('users')->find($id);
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