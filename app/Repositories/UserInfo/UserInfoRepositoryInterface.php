<?php
namespace App\Repositories\UserInfo;

interface UserInfoRepositoryInterface {
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}