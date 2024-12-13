<?php 
namespace App\Repositories\Service;

use App\Repositories\Service\ServiceRepositoryInterface;
use App\Models\Service;

class ServiceRepository implements ServiceRepositoryInterface {
    public function all() {
        return Service::all();
    }
    public function find($id) {
        return Service::find($id);
    }
    public function paginate($limit, $q) {
        $services = Service::query();

        $services->orderByDesc('created_at');
        return $limit ? $services->paginate($limit) : $services->get();
    }
    public function create(array $data) {
        return Service::create($data);
    }
    public function update($id, array $data) {
        $service = Service::find($id);
        return $service->update($data);
    }
    public function delete($id) {
        $service = Service::find($id);
        return $service->delete();
    }
}