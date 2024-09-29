<?php
namespace App\Repositories\MedicalHistory;

interface MedicalHistoryRepositoryInterface {
    public function all();
    public function paginate($limit, $q);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}