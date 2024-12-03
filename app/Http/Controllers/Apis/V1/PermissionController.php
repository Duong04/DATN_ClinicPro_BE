<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    private $permissionService;
    public function __construct(PermissionService $permissionService) {
        $this->permissionService = $permissionService;
    }

    public function paginate(Request $request) {
        return $this->permissionService->getPaginate($request);
    }

    public function show($id) {
        return $this->permissionService->findById($id);
    }

    public function create(PermissionRequest $request) {
        return $this->permissionService->create($request);
    }

    public function update(PermissionRequest $request, $id) {
        return $this->permissionService->update($request, $id);
    }

    public function delete($id) {
        return $this->permissionService->delete($id);
    }
}
