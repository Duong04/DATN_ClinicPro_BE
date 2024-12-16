<?php

namespace App\Repositories\Patient;

use App\Models\Patient;
use App\Models\PatientInfo;
use Illuminate\Support\Carbon;
use App\Repositories\Patient\PatientRepositoryInterface;

class PatientRepository implements PatientRepositoryInterface
{
    public function all()
    {
        return Patient::all();
    }

    public function paginate($limit, $q)
    {
        $patients = Patient::with(['identityCard', 'patientInfo', 'medicalHistories.user' => function ($query) {
            $query->select('id', 'email');
        }, 'medicalHistories.user.userInfo' => function ($query) {
            $query->select('id', 'fullname', 'avatar', 'user_id', 'phone_number');
        }, 'medicalHistories.user.doctor.specialty', 'medicalHistories.files'])
            ->when($q, function ($query, $q) {
                $query->orWhereHas('patientInfo', function ($query) use ($q) {
                    $query->where('fullname', 'LIKE', "%{$q}%")
                        ->orWhere('phone_number', $q)
                        ->orWhere('address', 'LIKE', "%{$q}%")
                        ->orWhere('email', 'LIKE', "%{$q}%");
                });
            });

        $patients->orderByDesc('created_at');

        return $limit ? $patients->paginate($limit) : $patients->get();
    }

    public function find($id)
    {
        return Patient::with(['identityCard', 'patientInfo', 'medicalHistories.user' => function ($query) {
            $query->select('id', 'email');
        }, 'medicalHistories.user.userInfo' => function ($query) {
            $query->select('id', 'fullname', 'avatar', 'user_id', 'phone_number');
        }, 'medicalHistories.user.doctor.specialty', 'medicalHistories.files'])->find($id);
    }
    public function create(array $data)
    {
        return Patient::create($data);
    }
    public function update($id, array $data)
    {
        $patient = Patient::find($id);
        return $patient->update($data);
    }
    public function delete($id)
    {
        $patient = Patient::find($id);
        return $patient->delete();
    }
    public function findOrFail($id)
    {
        return Patient::findOrFail($id);
    }

    public function statistics()
    {
        $result = Patient::selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as day,
        SUM(CASE WHEN WEEK(created_at) = WEEK(?) THEN 1 ELSE 0 END) as week,
        SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as month,
        SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as year
    ", [
            Carbon::today()->toDateString(),
            Carbon::now()->toDateString(),
            Carbon::now()->month,
            Carbon::now()->year,
            Carbon::now()->year
        ])->first();

        return [
            'total' => (int) $result->total,
            'day' => (int) $result->day,
            'week' => (int) $result->week,
            'month' => (int) $result->month,
            'year' => (int) $result->year,
        ];
    }
}
