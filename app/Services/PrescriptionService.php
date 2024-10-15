<?php

namespace App\Services;

use App\Repositories\Patient\PatientRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Prescription\PrescriptionRepositoryInterface;

class PrescriptionService
{

    private $prescriptionRepository;
    private $patientRepository;
    public function __construct(PrescriptionRepositoryInterface $prescriptionRepository, PatientRepositoryInterface $patientRepository)
    {
        $this->prescriptionRepository = $prescriptionRepository;
        $this->patientRepository = $patientRepository;
    }

    public function all()
    {
        return $this->prescriptionRepository->all();
    }
    public function find($id)
    {
        return $this->findPrescription($id);
    }

    public function listByIdPatient($id)
    {
        $this->findPatient($id);
        return $this->prescriptionRepository->findByIdPatient($id);
    }

    private function findPatient($id)
    {
        try {
            return $this->patientRepository->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Patient not found');
        }
    }

    public function create($request)
    {
        try {
            $data = $request->validated();
            return  $this->prescriptionRepository->create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create prescription: ' . $e->getMessage(),);
        }
    }
    public function update($request, $id)
    {
        try {
            $data = $request->validated();
            return $this->prescriptionRepository->update($id, $data);
        } catch (Exception $e) {
            throw new Exception('Failed to update prescription: ' . $e->getMessage(),);
        }
    }
    public function destroy($id)
    {
        try {
            return $this->prescriptionRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Prescription not found');
        }
    }

    private function findPrescription($id)
    {
        try {
            return $this->prescriptionRepository->find($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Prescription not found');
        }
    }
}
