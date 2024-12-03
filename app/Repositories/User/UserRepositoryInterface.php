<?php
namespace App\Repositories\User;

interface UserRepositoryInterface {
    public function all();
    public function paginate($limit, $q, $role, $department);
    public function getUserCheckDepartment($limit);
    public function getByRoleId($role_id, $limit, $q);
    public function find($id, array $relation);
    public function create(array $data);
    public function update($id, array $data);
    public function getBySpecialtyId($specialty_id);
    public function delete($id);
}