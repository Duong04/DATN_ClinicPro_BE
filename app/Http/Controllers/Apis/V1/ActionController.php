<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActionService;
use App\Http\Requests\ActionRequest;

class ActionController extends Controller
{
    private $actionService;
    public function __construct(ActionService $actionService) {
        $this->actionService = $actionService;
    }

    public function paginate(Request $request) {
        return $this->actionService->getPaginate($request);
    }

    public function show($id) {
        return $this->actionService->findById($id);
    }

    public function create(ActionRequest $request) {
        return $this->actionService->create($request);
    }

    public function update(ActionRequest $request, $id) {
        return $this->actionService->update($request, $id);
    }

    public function delete($id) {
        return $this->actionService->delete($id);
    }
}
