<?php

namespace App\Repositories\Prescription;

use App\Models\Prescription;

class PrescriptionRepository implements PrescriptionRepositoryInterface
{
    private $prescription;
    public function __construct(Prescription $prescription)
    {
        $this->prescription = $prescription;
    }

    public function all()
    {
        return $this->prescription::orderByDesc('created_at')->get();
    }
    public function find($id)
    {
        return $this->prescription::findOrFail($id);
    }
    public function findByIdPatient($id)
    {
        return $this->prescription::where('patient_id', $id)->orderByDesc('created_at')->get();
    }
    public function findByIdMedicalHistory($id)
    {
        return $this->prescription::where('medical_histories_id', $id)->with('medical_histories')->orderByDesc('created_at')->get();
    }
    public function create($data)
    {
        return $this->prescription::create($data);
    }
    public function update($id, $data)
    {
        $prescription = $this->find($id);
        $prescription->update($data);
        return $prescription;
    }
    public function destroy($id)
    {
        $prescription = $this->find($id);
        return $prescription->delete();
    }
}
