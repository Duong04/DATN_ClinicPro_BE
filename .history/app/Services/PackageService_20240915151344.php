<?php

namespace App\Services;

use App\Repositories\Package\PackageRepositoryInterface;
use App\Services\CloundinaryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PackageService
{
    private $packageRepository;
    private $cloundinaryService;

    public function __construct(PackageRepositoryInterface $packageRepository, CloundinaryService $cloundinaryService)
    {
        $this->packageRepository = $packageRepository;
        $this->cloundinaryService = $cloundinaryService;
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
            $package = $this->packageRepository->show($id);
            $this->cloundinaryService->delete($package->image);
            return $this->packageRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Package not found');
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
        try {
            return $this->packageRepository->{$action}($data ?? [], $id);
        } catch (\Throwable $th) {
            throw new \Exception($errorMessage . ': ' . $th->getMessage());
        }
    }
}
