<?php

namespace App\Repositories\Package;

interface PackageRepositoryInterface
{
    public function all();
    public function show($id);
    public function create($data);
    public function update($data, $id);
    public function destroy($id);
}
