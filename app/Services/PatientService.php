<?php
namespace App\Services;

use App\Repositories\Patient\PatientRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Models\PatientInfo;

class PatientService {
    private $patientRepository;
    private $patientInfoRepository;

    public function __construct(PatientInfoRepositoryInterface $patientInfoRepository, PatientRepositoryInterface $patientRepository) {
        $this->patientInfoRepository = $patientInfoRepository;
        $this->patientRepository = $patientRepository;
    }

    public function create($request) {
        try {
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();

            $patient = $this->patientRepository->update($id, $data);
            if ($data['patient_info']) {
                PatientInfo::where('patient_id', $id)->update($data['patient_info']); 
            }

            if ($data['medical_histories']) {
                
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }
}