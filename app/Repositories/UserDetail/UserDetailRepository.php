<?php
namespace App\Repositories\UserDetail;

use App\Repositories\UserDetail\UserDetailRepositoryInterface;

class UserDetailRepository implements UserDetailRepositoryInterface {
    public function all() {}
    public function paginate() {}
    public function find($id) {}
    public function create(array $data) {}
    public function update($id, array $data) {}
    public function delete($id) {}
}