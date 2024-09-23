<?php
namespace App\Repositories\Permission;

use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface {
    public function all() {
        return Permission::all();
    }
    public function paginate($limit, $q) {
        $actions = Permission::with('permissionActions');
        if ($q !== null) {
            $actions->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
            });
        }

        return $limit ? $actions->paginate($limit) : $actions->get();
    }
    public function find($id) {
        return Permission::with('permissionActions')->find($id);
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