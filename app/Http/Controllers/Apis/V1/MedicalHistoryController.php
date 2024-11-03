<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MedicalHistoryService;
use App\Http\Requests\MedicalHistoryRequest;

class MedicalHistoryController extends Controller
{
    private $medicalHistoryService;
    public function __construct(MedicalHistoryService $medicalHistoryService) {
        $this->medicalHistoryService = $medicalHistoryService;
    }
    public function paginate(Request $request) {
        return $this->medicalHistoryService->getPaginate($request);
    }

    public function show($id) {
        return $this->medicalHistoryService->findById($id);
    }

    public function getByPatientId($patient_id) {
        return $this->medicalHistoryService->getByPatientId($patient_id);
    }

    public function create(MedicalHistoryRequest $request) {
        return $this->medicalHistoryService->create($request);
    }

    public function update(MedicalHistoryRequest $request, $id) {
        return $this->medicalHistoryService->update($request, $id);
    }

    public function delete($id) {
        return $this->medicalHistoryService->delete($id);
    }
}
