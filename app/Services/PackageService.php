<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\CategoryPackage;
use App\Services\CloundinaryService;
use App\Http\Resources\PackageResource;
use App\Repositories\Package\PackageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\PackageCategory\PackageCategoryRepositoryInterface;

class PackageService
{
    private $packageRepository;
    private $cloundinaryService;
    private $packageCategoryRepository;
    public function __construct(PackageRepositoryInterface $packageRepository, CloundinaryService $cloundinaryService, PackageCategoryRepositoryInterface $packageCategoryRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->cloundinaryService = $cloundinaryService;
        $this->packageCategoryRepository = $packageCategoryRepository;
    }

    public function create($request)
    {
        return $this->handleRequest($request, 'create', 'Failed to create package');
    }

    public function update($request, $id)
    {
        return $this->handleRequest($request, 'update', 'Failed to update package', $id);
    }

    public function getAll()
    {
        return $this->packageRepository->all();
    }

    public function slug($slug)
    {
        try {
            return $this->packageRepository->slug($slug);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Package not found');
        }
    }

    public function show($id)
    {
        try {
            return $this->packageRepository->show($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Package not found');
        }
    }

    public function destroy($id)
    {
        try {
            return $this->packageRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Package not found');
        }
    }

    public function getByCategory($id)
    {
        try {
            $categoryPackage = CategoryPackage::with('packages')->findOrFail($id);
            return PackageResource::collection($categoryPackage->packages);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Package category not found');
        }
    }

    private function handleRequest($request, $action, $errorMessage, $id = null)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'packages/';
            $data['image'] = $this->cloundinaryService->upload($file, $folder);
        }
        $data['slug'] = Str::slug($data["name"]);
        try {
            return $this->packageRepository->{$action}($data ?? [], $id);
        } catch (\Throwable $th) {
            throw new \Exception($errorMessage . ': ' . $th->getMessage());
        }
    }
}
