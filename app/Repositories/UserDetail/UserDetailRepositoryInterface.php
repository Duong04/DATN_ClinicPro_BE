<?php
namespace App\Repositories\UserDetail;

interface UserDetailRepositoryInterface {
    public function all();
    public function paginate();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}