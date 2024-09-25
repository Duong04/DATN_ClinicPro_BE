<?php

namespace App\Http\Controllers\Apis\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DepartmentService;
use App\Http\Requests\DepartmentRequest;

class DepartmentController extends Controller
{
    private $departmentService;

    public function __construct(DepartmentService $departmentService) {
        $this->departmentService = $departmentService;
    }

    public function paginate(Request $request) {
        return $this->departmentService->getPaginate($request);
    }

    public function show($id) {
        return $this->departmentService->findById($id);
    }

    public function create(DepartmentRequest $request) {
        return $this->departmentService->create($request);
    }

    public function update(DepartmentRequest $request, $id) {
        return $this->departmentService->update($request, $id);
    }

    public function delete($id) {
        return $this->departmentService->delete($id);
    }
}
