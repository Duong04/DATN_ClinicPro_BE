<?php
namespace App\Repositories\User;

interface UserRepositoryInterface {
    public function all();
    public function paginate($limit, $q);
    public function find($id, array $relation);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}