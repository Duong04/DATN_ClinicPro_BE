<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PatientService;
use App\Http\Requests\PatientRequest;

class PatientController extends Controller
{
    private $patientService;

    public function __construct(PatientService $patientService) {
        $this->patientService = $patientService;
    }

    public function paginate(Request $request) {
        return $this->patientService->getPaginate($request);
    }

    public function show($id) {
        return $this->patientService->findById($id);
    }

    public function create(PatientRequest $request) {
        return $this->patientService->create($request);
    }

    public function update(PatientRequest $request, $id) {
        return $this->patientService->update($request, $id);
    }
    
}
