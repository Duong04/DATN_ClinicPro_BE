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

    public function store(AppointmentRequest $request)
    {
        return  $this->respondWithData(fn() =>   $this->appointmentService->create($request), '', 201);
    }

    public function cancel(Request $request, $id)
    {
        return $this->respondWithData(fn() => $this->appointmentService->cancel($request, $id), '', 200);;
    }

    public function destroy($id)
    {
        return $this->respondWithData(fn() => $this->appointmentService->delete($id), '', 204);
    }

    private function respondWithData(callable $callback, string $message = 'Failed to process request', int $successStatus = 200): JsonResponse
    {
        try {
            $data = $callback();
            return response()->json(['data' => $data], $successStatus);
        } catch (\Exception $e) {
            $status = $e->getMessage() === 'Appointment not found' ? 404 : 500;
            return response()->json(['success' => false, 'message' => $e->getMessage() ?: $message], $status);
        }
    }
}
