<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService) {
        $this->roleService = $roleService;
    }

    public function paginate(Request $request) {
        return $this->roleService->getPaginate($request);
    }

    public function show($id) {
        return $this->roleService->findById($id);
    }

    public function create(RoleRequest $request) {
        return $this->roleService->create($request);
    }

    public function update(RoleRequest $request, $id) {
        return $this->roleService->update($request, $id);
    }

    public function delete($id) {
        return $this->roleService->delete($id);
    }
}
