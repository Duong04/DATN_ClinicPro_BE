<?php
namespace App\Repositories\RolePermission;

use App\Repositories\RolePermission\RolePermissionRepositoryInterface;
use App\Models\RolePermission;

class RolePermissionRepository implements RolePermissionRepositoryInterface {
    public function all() {
        
    }
    public function paginate() {
        
    }
    public function find($id) {
        
    }
    public function create(array $data) {
        return RolePermission::create($data);
    }
    public function update($id, array $data) {
        
    }
    public function delete($id) {
        return RolePermission::where('role_id', $id)->delete();
    }
}