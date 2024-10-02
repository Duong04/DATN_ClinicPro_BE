<?php

namespace App\Repositories\Patient;

use App\Models\PatientInfo;
use App\Repositories\Patient\PatientRepositoryInterface;
use App\Models\Patient;

class PatientRepository implements PatientRepositoryInterface
{
    public function all()
    {
        return Patient::all();
    }
    public function paginate($limit, $q) {
        $patients = Patient::with('identityCard', 'patientInfo')
            ->when($q, function ($query, $q) {
                $query->orWhereHas('patientInfo', function ($query) use ($q) {
                    $query->where('fullname', 'LIKE', "%{$q}%")
                          ->orWhere('phone_number', 'LIKE', "%{$q}%")
                          ->orWhere('address', 'LIKE', "%{$q}%")
                          ->orWhere('email', 'LIKE', "%{$q}%");
                });
            });

        $patients->orderByDesc('created_at');
    
        return $limit ? $patients->paginate($limit) : $patients->get();
    }
    
    public function find($id)
    {
        return Patient::with('patientInfo', 'identityCard')->find($id);
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
