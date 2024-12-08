<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\ServiceProcessor;
use App\Http\Requests\ServiceRequest;

class ServiceController extends Controller
{
    private $serviceProcessor;
    public function __construct(ServiceProcessor $serviceProcessor) {
        $this->serviceProcessor = $serviceProcessor;
    }

    public function paginate(Request $request) {
        return $this->serviceProcessor->getPaginate($request);
    }

    public function create(ServiceRequest $request) {
        return $this->serviceProcessor->create($request);
    }

    public function update(ServiceRequest $request, $id) {
        return $this->serviceProcessor->update($request, $id);
    }

    public function show($id) {
        return $this->serviceProcessor->findById($id);
    }

    public function delete($id) {
        return $this->serviceProcessor->delete($id);
    }
}
