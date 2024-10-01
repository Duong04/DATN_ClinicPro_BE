<?php

namespace App\Repositories\Patient;

use App\Repositories\Patient\PatientRepositoryInterface;
use App\Models\Patient;

class PatientRepository implements PatientRepositoryInterface
{
    public function all()
    {
        return Patient::all();
    }
    public function paginate($limit, $q) {}
    public function find($id)
    {
        return Patient::findOrFail($id);
    }
    public function create(array $data)
    {
        return Patient::create($data);
    }
    public function update($id, array $data)
    {
        $patient = Patient::find($id);
        return $patient->update($data);
    }
    public function delete($id)
    {
        $patient = Patient::find($id);
        return $patient->delete();
    }
}
