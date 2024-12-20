<?php

namespace App\Repositories\Patient;

interface PatientRepositoryInterface
{
    public function all();
    public function paginate($limit, $q);
    public function find($id);
    public function findOrFail($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
