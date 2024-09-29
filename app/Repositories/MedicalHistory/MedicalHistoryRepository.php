<?php
namespace App\Repositories\MedicalHistory;

use App\Repositories\MedicalHistory\MedicalHistoryRepositoryInterface;
use App\Models\MedicalHistory;
class MedicalHistoryRepository implements MedicalHistoryRepositoryInterface {
    public function all() {
        return MedicalHistory::all();
    }
    public function paginate($limit, $q) {
        $roles = MedicalHistory::with('manager')->withCount('users');
        if ($q) {
            $roles->where('name', 'like', "%{$q}%");
        }

        return $limit ? $roles->paginate($limit) : $roles->get();
    }
    public function find($id) {
        return MedicalHistory::with('manager')->withCount('users')->find($id);
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