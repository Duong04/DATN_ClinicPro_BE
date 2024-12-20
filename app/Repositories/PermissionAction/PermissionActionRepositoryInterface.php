<?php
namespace App\Repositories\PermissionAction;

interface PermissionActionRepositoryInterface {
    public function all();
    public function paginate();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($col, $id);
}