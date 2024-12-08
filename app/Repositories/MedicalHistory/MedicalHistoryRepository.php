<?php
namespace App\Repositories\MedicalHistory;

use App\Repositories\MedicalHistory\MedicalHistoryRepositoryInterface;
use App\Models\MedicalHistory;
class MedicalHistoryRepository implements MedicalHistoryRepositoryInterface {
    public function all() {
        return MedicalHistory::all();
    }
    public function paginate($limit, $q) {
        $medicalHistories = MedicalHistory::with('files', 'user.userInfo', 'user.doctor.specialty', 'patient.patientInfo');
        if ($q) {
            $medicalHistories->where('diagnosis', 'like', "%{$q}%")
            ->orWhere('treatment', 'like', "%{$q}%")
            ->orWhereHas('doctor.user.userInfo', function($query) use ($q) {
                $query->where('fullname', 'like', "%{$q}%");
            })
            ->orWhereHas('doctor.user', function($query) use ($q) {
                $query->where('email', 'like', "%{$q}%");
            })
            ->orWhereHas('patient.patientInfo', function($query) use ($q) {
                $query->where('fullname', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%");;
            });
        }

        $medicalHistories->orderByDesc('created_at');

        return $limit ? $medicalHistories->paginate($limit) : $medicalHistories->get();
    }
    public function find($id) {
        return MedicalHistory::with(['files', 'user', 'patient', 'services'])->find($id);
    }
    public function getByPatientId($patient_id) {
        return MedicalHistory::with('files', 'user', 'patient')->where('patient_id', $patient_id)->get();
    }
    public function create(array $data) {
        return MedicalHistory::create($data);
    }
    public function update($id, array $data) {
        $MedicalHistory = MedicalHistory::find($id);
        return $MedicalHistory->update($data);
    }
    public function delete($id) {
        $MedicalHistory = MedicalHistory::find($id);
        return $MedicalHistory->delete();
    }
}