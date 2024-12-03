<?php

namespace App\Http\Controllers\Apis\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\PackageCategoryService;
use App\Http\Requests\PackageCategoryRequest;

class PackageCategoryController extends Controller
{
    private $packageCategoryService;
    public function __construct(PackageCategoryService $packageCategoryService)
    {
        $this->packageCategoryService = $packageCategoryService;
    }

    public function index()
    {
        return $this->respondWithData(fn() => $this->packageCategoryService->all());
    }

    public function show($id)
    {
        return $this->respondWithData(fn() => $this->packageCategoryService->find($id));
    }
    public function slug($slug)
    {
        return $this->respondWithData(fn() => $this->packageCategoryService->slug($slug));
    }
    public function store(PackageCategoryRequest $request)
    {
        return $this->respondWithData(fn() => $this->packageCategoryService->create($request), '', 201);
    }
    public function update(PackageCategoryRequest $request, String $id)
    {
        return $this->respondWithData(fn() => $this->packageCategoryService->update($request, $id), '', 201);
    }
    public function destroy($id)
    {
        return $this->respondWithData(fn() => $this->packageCategoryService->delete($id), '', 204);
    }

    private function respondWithData(callable $callback, string $message = 'Failed to process request', int $successStatus = 200): JsonResponse
    {
        try {
            $data = $callback();
            return response()->json(['data' => $data], $successStatus);
        } catch (\Exception $e) {
            $status = str_contains($e->getMessage(), 'not found')  ? 404 : 500;
            return response()->json(['success' => false, 'message' => $e->getMessage() ?: $message], $status);
        }
    }
}
