<?php

namespace App\Repositories\Package;

use App\Models\Package;

class PackageRepository implements PackageRepositoryInterface
{
    private $package;
    public function __construct(Package $package)
    {
        $this->package = $package;
    }
    public function all()
    {
        return $this->package::orderByDesc('created_at')->get();
    }
    public function show($id)
    {
        return $this->package::findOrFail($id);
    }

    public function slug($slug)
    {
        return $this->package::where('slug', $slug)->firstOrFail();
    }
    public function getBySpecialties($id)
    {
        return $this->package::where('specialty_id', $id)->orderByDesc('created_at')->get();
    }

    public function create($data)
    {
        $result = $this->package::create($data);
        return  $result;
    }
    public function update($data, $id)
    {
        $package = $this->package::findOrFail($id);
        $package->update($data);
        return $package;
    }

    public function destroy($id)
    {
        $package = $this->package::findOrFail($id);
        return $package->delete();
    }
}
