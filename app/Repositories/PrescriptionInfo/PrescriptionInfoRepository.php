<?php

namespace App\Repositories\PrescriptionInfo;

use App\Models\PrescriptionInfo;

class PrescriptionInfoRepository implements PrescriptionInfoRepositoryInterface
{
    private $prescriptionInfo;
    public function __construct(PrescriptionInfo $prescriptionInfo)
    {
        $this->prescriptionInfo = $prescriptionInfo;
    }
    public function all()
    {
        return $this->prescriptionInfo::all();
    }
    public function find($id)
    {
        return $this->prescriptionInfo::findOrFail($id);
    }
    public function create($data)
    {
        return $this->prescriptionInfo::create($data);
    }
    public function update($id, $data)
    {
        $prescription = $this->find($id);
        return $prescription->update($data);
    }
    public function destroy($id)
    {
        $prescription = $this->find($id);
        return $prescription->delete();
    }
}
