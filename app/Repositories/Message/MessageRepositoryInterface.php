<?php
namespace App\Repositories\Message;

interface MessageRepositoryInterface {
    public function all();
    public function find($id);
    public function paginate($limit);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
