<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\CategoryPackage;
use App\Services\CloundinaryService;
use App\Http\Resources\PackageResource;
use App\Repositories\Package\PackageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\PackageCategory\PackageCategoryRepositoryInterface;
use App\Repositories\Specialty\SpecialtyRepositoryInterface;

class PackageService
{
    private $packageRepository;
    private $specialtyRepository;
    public function __construct(PackageRepositoryInterface $packageRepository, SpecialtyRepositoryInterface $specialtyRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->specialtyRepository = $specialtyRepository;
    }

    public function create($request)
    {
        return $this->handleRequest($request, 'create', 'Tạo gói khám thất bại');
    }

    public function update($request, $id)
    {
        return $this->handleRequest($request, 'update', 'Sửa gói khám thất bại', $id);
    }

    public function getAll()
    {
        return $this->packageRepository->all();
    }
    public function getBySpecialties($request)
    {
        $specialty_id = $request->query('specialty_id');

        if (!$specialty_id) {
            throw new \InvalidArgumentException('ID chuyên khoa là bắt buộc');
        }
        $specialties = $this->specialtyRepository->find($specialty_id);
        if (!$specialties) {
            throw new \Exception('Chuyên khoa không tồn tại');
        }
        return $this->packageRepository->getBySpecialties($specialty_id);
    }

    public function slug($slug)
    {
        try {
            return $this->packageRepository->slug($slug);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Gói khám không tồn tại');
        }
    }

    public function show($id)
    {
        try {
            return $this->packageRepository->show($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Gói khám không tồn tại');
        }
    }

    public function destroy($id)
    {
        try {
            return $this->packageRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Gói khám không tồn tại');
        }
    }

    public function getByCategory($id)
    {
        try {
            $categoryPackage = CategoryPackage::with('packages')->findOrFail($id);
            return PackageResource::collection($categoryPackage->packages);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Danh mục gói khám không tồn tại');
        }
    }

    private function handleRequest($request, $action, $errorMessage, $id = null)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data["name"]);
        try {
            return $this->packageRepository->{$action}($data ?? [], $id);
        } catch (\Throwable $th) {
            throw new \Exception($errorMessage . ': ' . $th->getMessage());
        }
    }
}
