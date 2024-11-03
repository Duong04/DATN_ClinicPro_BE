<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\PackageCategory\PackageCategoryRepositoryInterface;

class PackageCategoryService
{
    private $packageServiceRepository;
    public function __construct(PackageCategoryRepositoryInterface $packageServiceRepository)
    {
        $this->packageServiceRepository = $packageServiceRepository;
    }

    public function all()
    {
        return $this->packageServiceRepository->all();
    }
    public function find($id)
    {
        try {
            return $this->packageServiceRepository->find($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Package category not found');
        }
    }
    public function slug($slug)
    {
        try {
            return $this->packageServiceRepository->slug($slug);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Package category not found');
        }
    }
    public function create($request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);
            return $this->packageServiceRepository->create($data);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
    public function update($request, $id)
    {
        try {
            $this->find($id);
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);
            return $this->packageServiceRepository->update($id, $data);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            return $this->packageServiceRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Package category not found');
        }
    }
}
