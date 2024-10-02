<?php

namespace App\Http\Controllers\Apis\v1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\PrescriptionService;
use App\Http\Requests\PrescriptionRequest;

class PrescriptionController extends Controller
{
    private $prescriptionService;
    public function __construct(PrescriptionService $prescriptionService)
    {
        $this->prescriptionService = $prescriptionService;
    }
    public function index()
    {
        return $this->respondWithData(fn() => $this->prescriptionService->all());
    }
    public function show($id)
    {
        return $this->respondWithData(fn() => $this->prescriptionService->find($id));
    }
    public function listById($id)
    {
        return $this->respondWithData(fn() => $this->prescriptionService->listByIdPatient($id));
    }
    public function store(PrescriptionRequest $request)
    {
        return $this->respondWithData(fn() => $this->prescriptionService->create($request), '', 201);
    }
    public function update(PrescriptionRequest $request, $id)
    {
        return $this->respondWithData(fn() => $this->prescriptionService->update($request, $id), '', 200);
    }
    public function destroy($id)
    {
        return $this->respondWithData(fn() => $this->prescriptionService->destroy($id), '', 204);
    }

    private function respondWithData(callable $callback, string $message = 'Failed to process request', int $successStatus = 200): JsonResponse
    {
        try {
            $data = $callback();
            return response()->json(['data' => $data], $successStatus);
        } catch (\Exception $e) {
            $status = $e->getMessage() === 'Prescription not found' ? 404 : 500;
            return response()->json(['success' => false, 'message' => $e->getMessage() ?: $message], $status);
        }
    }
}
