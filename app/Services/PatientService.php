<?php
namespace App\Services;

use App\Repositories\Patient\PatientRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Models\IdentityCard;
use App\Models\PatientInfo;

class PatientService {
    private $patientRepository;
    private $patientInfoRepository;

    public function __construct(PatientInfoRepositoryInterface $patientInfoRepository, PatientRepositoryInterface $patientRepository) {
        $this->patientInfoRepository = $patientInfoRepository;
        $this->patientRepository = $patientRepository;
    }

    public function getPaginate($request) {
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function findById($id) {
        try {
            $patient = $this->patientRepository->find($id);

            if (empty($patient)) {
                return response()->json(['error' => 'Không timg thấy thông tin bệnh nhân!'], 404);
            }

            return response()->json(['data' => $patient], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();

            if (isset($data['identity_card'])) {
                $identityCard = IdentityCard::create($data['identity_card']);
            }
            $data['identity_card'] = $identityCard->id;

            $patient = $this->patientRepository->create($data);
            if (isset($data['user_info'])) {
                $data['user_info']['patient_id'] = $patient->id;
                $this->patientInfoRepository->create($data['user_info']);
            }

            $patient = $this->patientRepository->find($patient->id);
            return response()->json(['message' => 'Thông tin bệnh nhân đã được tạo', 'data' => $patient], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();

            $patient = $this->patientRepository->find($id);
            $this->patientRepository->update($id, $data);
            if (isset($data['identity_card'])) {
               $patient->identityCard->update($data['identity_card']);
            }

            if ($data['patient_info']) {
                PatientInfo::where('patient_id', $id)->update($data['patient_info']); 
            }

            return response()->json(['message' => 'Cập nhật thông tin bệnh nhân thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

}