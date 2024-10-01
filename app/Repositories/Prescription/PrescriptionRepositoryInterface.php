<?php

namespace App\Repositories\Prescription;

interface PrescriptionRepositoryInterface
{
    public function all();
    public function find($id);
    public function findByIdPatient($id);
    public function create($data);
    public function update($id, $data);
    public function destroy($id);
}
