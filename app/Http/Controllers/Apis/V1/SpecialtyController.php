<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SpecialtyService;
use App\Http\Requests\SpecialtyRequest;

class SpecialtyController extends Controller
{
    private $specialtyService;

    public function __construct(SpecialtyService $specialtyService) {
        $this->specialtyService = $specialtyService;
    }

    public function paginate(Request $request) {
        return $this->specialtyService->getPaginate($request);
    }

    public function show($id) {
        return $this->specialtyService->findById($id);
    }

    public function create(SpecialtyRequest $request) {
        return $this->specialtyService->create($request);
    }

    public function update(SpecialtyRequest $request, $id) {
        return $this->specialtyService->update($request, $id);
    }

    public function delete($id) {
        return $this->specialtyService->delete($id);
    }
}
