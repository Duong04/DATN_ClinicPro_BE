<?php

namespace App\Http\Controllers\Apis\V1;

use App\Services\PackageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    private $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index(): JsonResponse
    {
        return $this->respondWithData(fn() => $this->packageService->getAll());
    }

    public function store(PackageRequest $request): JsonResponse
    {
        return $this->respondWithData(fn() => $this->packageService->create($request), 'Package created successfully!', 201);
    }

    public function show(string $id): JsonResponse
    {
        dd('show');
        return $this->respondWithData(fn() => $this->packageService->show($id),);
    }

    public function slug($slug): JsonResponse
    {
        dd('slug');

        return $this->respondWithData(fn() => $this->packageService->slug($slug),);
    }

    public function update(PackageRequest $request, string $id): JsonResponse
    {
        return $this->respondWithData(fn() => $this->packageService->update($request, $id), '', 201);
    }

    public function destroy(string $id): JsonResponse
    {
        return $this->respondWithData(fn() => $this->packageService->destroy($id), '', 204);
    }

    private function respondWithData(callable $callback, string $message = 'Failed to process request', int $successStatus = 200): JsonResponse
    {
        try {
            $data = $callback();
            return response()->json(['data' => $data], $successStatus);
        } catch (\Exception $e) {
            $status = $e->getMessage() === 'Package not found' ? 404 : 500;
            return response()->json(['success' => false, 'message' => $e->getMessage() ?: $message], $status);
        }
    }
}
