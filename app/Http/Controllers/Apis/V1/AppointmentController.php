<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class AppointmentController extends Controller
{

    private $appointmentService;
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    public function index()
    {
        return  $this->respondWithData(fn() => $this->appointmentService->all());
    }

    public function show($id)
    {
        return   $this->respondWithData(fn() => $this->appointmentService->show($id), '', 200);
    }
    public function findByIdPatient($id)
    {
        return $this->respondWithData(fn() => $this->appointmentService->findByIdPatient($id));
    }

    public function store(AppointmentRequest $request)
    {
        return  $this->respondWithData(fn() =>   $this->appointmentService->create($request), '', 201);
    }

    public function cancel(Request $request, $id)
    {
        return $this->respondWithData(fn() => $this->appointmentService->cancel($request, $id), '', 200);;
    }

    public function update($id)
    {
        return $this->respondWithData(fn() => $this->appointmentService->update($id));
    }
    public function assign(Request $request, $id)
    {
        return $this->respondWithData(fn() => $this->appointmentService->assign($id, $request));
    }
    public function complete($id)
    {
        return $this->respondWithData(fn() => $this->appointmentService->complete($id));
    }

    public function destroy($id)
    {
        return $this->respondWithData(fn() => $this->appointmentService->delete($id), '', 204);
    }

    private function respondWithData(callable $callback, string $message = 'Failed to process request', int $successStatus = 200): JsonResponse
    {
        try {
            $data = $callback();
            if (isset($data['error']) && $data['error']) {
                return response()->json([
                    'error' => true,
                    'message' => $data['error']
                ], 400);
            }
            return response()->json(['data' => $data], $successStatus);
        } catch (\Exception $e) {
            $status = str_contains($e->getMessage(), 'not found') ? 404 : 500;
            return response()->json(['success' => false, 'message' => $e->getMessage() ?: $message], $status);
        }
    }
}
