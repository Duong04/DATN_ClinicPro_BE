<?php

namespace App\Services;

use Exception;
use App\Repositories\Patient\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Prescription\PrescriptionRepositoryInterface;
use App\Repositories\PrescriptionInfo\PrescriptionInfoRepositoryInterface;
use App\Http\Resources\PrescriptionResource;
use App\Models\MedicalHistory;

class PrescriptionService
{
    private $prescriptionRepository;
    private $patientRepository;
    private $prescriptionInfoRepository;

    public function __construct(
        PrescriptionRepositoryInterface $prescriptionRepository,
        PatientRepositoryInterface $patientRepository,
        PrescriptionInfoRepositoryInterface $prescriptionInfoRepository
    ) {
        $this->prescriptionRepository = $prescriptionRepository;
        $this->patientRepository = $patientRepository;
        $this->prescriptionInfoRepository = $prescriptionInfoRepository;
    }

    public function all()
    {
        return PrescriptionResource::collection($this->prescriptionRepository->all());
    }

    public function find($id)
    {
        $prescription = $this->findPrescription($id);
        return new PrescriptionResource($prescription);
    }

    public function listByIdPatient($id)
    {
        $this->findPatient($id);
        $prescriptions = $this->prescriptionRepository->findByIdPatient($id);
        return PrescriptionResource::collection($prescriptions);
    }
    public function getByMedicalHistory($id)
    {
        $this->findMedicalHistory($id);
        $prescriptions = $this->prescriptionRepository->findByIdMedicalHistory($id);
        return PrescriptionResource::collection($prescriptions);
    }

    public function create($request)
    {
        try {
            $dataRequest = $request->validated();

            if (!$this->checkIsValidMedicalHistory($dataRequest['patient_id'], $dataRequest['medical_histories_id'])) {
                throw new \Exception('ID bệnh nhân không khớp với ID lịch sử y tế');
            }

            $prescriptionData = $this->buildPrescriptionData($dataRequest);
            $prescription = $this->prescriptionRepository->create($prescriptionData);

            $this->processMedications($dataRequest['medications'], $prescription->id);

            return new PrescriptionResource($prescription);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function update($request, $id)
    {
        try {
            $dataRequest = $request->validated();

            $prescriptionData = $this->buildPrescriptionData($dataRequest);
            $prescription = $this->prescriptionRepository->update($id, $prescriptionData);

            $this->processMedications($dataRequest['medications'], $prescription->id, true);

            return new PrescriptionResource($prescription);
        } catch (Exception $e) {
            throw new Exception('Failed to update prescription: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            return $this->prescriptionRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Đơn thước không tồn tại');
        }
    }

    private function findPatient($id)
    {
        try {
            return $this->patientRepository->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('bệnh nhân không tồn tại');
        }
    }

    private function findMedicalHistory($id)
    {
        try {
            return MedicalHistory::with('prescriptions')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Hồ sơ bệnh án không tồn tại');
        }
    }

    private function findPrescription($id)
    {
        try {
            return $this->prescriptionRepository->find($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Đơn thuốc không tồn tại');
        }
    }

    private function buildPrescriptionData($dataRequest)
    {
        return [
            'name' => $dataRequest['name'],
            'description' => $dataRequest['description'],
            'user_id' => $dataRequest['user_id'],
            'patient_id' => $dataRequest['patient_id'],
            'medical_histories_id' => $dataRequest['medical_histories_id']
        ];
    }

    private function processMedications($medications, $prescriptionId, $isUpdate = false)
    {
        foreach ($medications as $item) {
            $medicationData = [
                'prescription_id' => $prescriptionId,
                'instructions' => $item['instructions'],
                'medication_id' => $item['medication_id'],
                'duration' => $item['duration'],
                'quantity' => $item['quantity']
            ];

            if ($isUpdate && isset($item['id'])) {
                $this->prescriptionInfoRepository->update($item['id'], $medicationData);
            } else {
                $this->prescriptionInfoRepository->create($medicationData);
            }
        }
    }
    private function checkIsValidMedicalHistory($patient_id, $medical_histories_id): bool
    {
        $medical_history = MedicalHistory::find($medical_histories_id);

        return $medical_history && $medical_history->patient_id == $patient_id;
    }
}
