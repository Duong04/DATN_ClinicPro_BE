<?php
namespace App\Repositories\Specialty;

use App\Repositories\Specialty\SpecialtyRepositoryInterface;
use App\Models\Specialty;

class SpecialtyRepository implements SpecialtyRepositoryInterface {
    public function all() {
        
    }
    public function paginate($limit, $q) {
        $specialties = Specialty::withCount('doctors');
        if ($q) {
            $specialties->where('name', 'like', "%{$q}%");
        }

        $specialties->orderByDesc('created_at');

        return $limit ? $specialties->paginate($limit) : $specialties->get();
    }
    public function find($id) {
        return Specialty::withCount('doctors')->find($id);
    }
    public function create(array $data) {
        return Specialty::create($data);
    }
    public function update($id, array $data) {
        $specialty = Specialty::find($id);
        return $specialty->update($data);
    }
    public function delete($id) {
        $specialty = Specialty::find($id);
        return $specialty->delete();
    }
}