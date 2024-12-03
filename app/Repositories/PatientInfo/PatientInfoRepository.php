<?php
namespace App\Repositories\PatientInfo;

use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use App\Models\PatientInfo;

class PatientInfoRepository implements PatientInfoRepositoryInterface {
    public function all() {
        return PatientInfo::all();
    }
    public function find($id) {
        return PatientInfo::find($id);
    }
    public function create(array $data) {
        return PatientInfo::create($data);
    }
    public function update($id, array $data) {
        $patientInfo = PatientInfo::find($id);
        return $patientInfo->update($data);
    }
    public function delete($id) {
        $patientInfo = PatientInfo::find($id);
        return $patientInfo->delete();
    }
}