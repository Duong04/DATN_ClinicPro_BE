<?php
namespace App\Repositories\Permission;

use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface {
    public function all() {
        return Permission::all();
    }
    public function paginate($limit, $q) {
        $permissions = Permission::with('permissionActions', 'action');
        if ($q !== null) {
            $permissions->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $permissions->orderByDesc('created_at');

        return $limit ? $permissions->paginate($limit) : $permissions->get();
    }
    public function find($id) {
        return Permission::with('permissionActions', 'action')->find($id);
    }
    public function create(array $data) {
        return Permission::create($data);
    }
    public function update($id, array $data) {
        $permission = Permission::find($id);
        return $permission->update($data);
    }
    public function delete($id) {
        $permission = Permission::find($id);
        return $permission->delete();
    }
}