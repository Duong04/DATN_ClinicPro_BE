<?php
namespace App\Repositories\UserInfo;

use App\Repositories\UserInfo\UserInfoRepositoryInterface;
use App\Models\UserInfo;

class UserInfoRepository implements UserInfoRepositoryInterface {
    public function all() {
        return UserInfo::all();
    }
    public function find($id) {
        return UserInfo::find($id);
    }
    public function create(array $data) {
        return UserInfo::create($data);
    }
    public function update($id, array $data) {
        $userInfo = UserInfo::find($id);
        return $userInfo->update($data);
    }
    public function delete($id) {
        $userInfo = UserInfo::find($id);
        return $userInfo->delete();
    }
}