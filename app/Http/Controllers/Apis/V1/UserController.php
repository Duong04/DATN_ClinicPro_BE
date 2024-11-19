<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function paginate(Request $request) {
        return $this->userService->getPaginate($request);
    }

    public function getUserCheckDepartment(Request $request) {
        return $this->userService->getUserCheckDepartment($request);
    }

    public function getByRole(Request $request, $id) {
        return $this->userService->getByRoleId($request, $id);
    }

    public function create(UserRequest $request) {
        return $this->userService->create($request);
    }

    public function update(UserRequest $request, $id) {
        return $this->userService->update($request, $id);
    }

    public function show($id) {
        return $this->userService->findById($id);
    }

    public function getBySpecialtyId($specialtyId) {
        return $this->userService->getBySpecialtyId($specialtyId);
    }
}
