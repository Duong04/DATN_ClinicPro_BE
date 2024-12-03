<?php

namespace App\Repositories\PackageCategory;

use App\Models\CategoryPackage;

class  PackageCategoryRepository implements PackageCategoryRepositoryInterface
{
    private $packageCategory;
    public function __construct(CategoryPackage $categoryPackage)
    {
        $this->packageCategory = $categoryPackage;
    }
    public function all()
    {
        return $this->packageCategory::all();
    }
    public function find($id)
    {
        return $this->packageCategory::findOrFail($id);
    }
    public function slug($slug)
    {
        return $this->packageCategory::where('slug', $slug)->firstOrFail();
    }
    public function create($data)
    {
        return  $this->packageCategory->create($data);
    }
    public function update($id, $data)
    {
        $result = $this->find($id);
        $result->update($data);
        return $result;
    }
    public function delete($id)
    {
        $result = $this->find($id);
        return $result->delete();
    }
}
