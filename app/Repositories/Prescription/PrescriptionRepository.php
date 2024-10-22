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
        return $this->prescription::all();
    }
    public function find($id)
    {
        return $this->prescription::findOrFail($id);
    }
    public function findByIdPatient($id)
    {
        return $this->prescription::where('patient_id', $id)->get();
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
