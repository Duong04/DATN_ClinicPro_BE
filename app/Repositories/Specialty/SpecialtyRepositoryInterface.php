<?php
namespace App\Repositories\Specialty;

interface SpecialtyRepositoryInterface {
    public function all();
    public function paginate($limit, $q);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}