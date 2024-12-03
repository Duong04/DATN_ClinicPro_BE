<?php

namespace App\Repositories\PackageCategory;

interface PackageCategoryRepositoryInterface
{
    public function all();
    public function find($id);
    public function slug($slug);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
}
