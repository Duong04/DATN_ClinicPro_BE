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

    public function create(UserRequest $request) {
        return $this->userService->create($request);
    }

    public function update(UserRequest $request, $id) {
        return $this->userService->update($id, $request);
    }

    public function show($id) {
        return $this->userService->findById($id);
    }
}