<?php 
namespace App\Repositories\Service;

interface ServiceRepositoryInterface {
    public function all();
    public function find($id);
    public function paginate($limit, $q);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}