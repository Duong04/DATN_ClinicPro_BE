<?php

namespace App\Repositories\Appointment;

use DB;
use App\Models\Appointment;
use Illuminate\Support\Carbon;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    private $appointment;
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function all()
    {
        return $this->appointment::with([
            'patient',
            'package',
            'specialty',
            'user.role',
            'user.userInfo'
        ])->orderByDesc('created_at')->get();
    }
    // public function all()
    // {
    //     return $this->appointment::with(
    //         ['patient' => function ($query) {
    //             $query->select('id', 'insurance_number', 'identity_card_id', 'status');
    //         }, 'package' => function ($query) {
    //             $query->select('id', 'name', 'description', 'content', 'image', 'slug');
    //         }, 'specialty' => function ($query) {
    //             $query->select('id', 'name', 'description');
    //         }, 'user' => function ($query) {
    //             $query->select('id', 'status', 'role_id');
    //         }, 'user.role' => function ($query) {
    //             $query->select('id', 'name', 'description');
    //         }, 'user.userInfo' => function ($query) {
    //             $query->select('id', 'fullname', 'address', 'avatar', 'phone_number', 'gender', 'dob', 'identity_card_id');
    //         }]
    //     )->orderByDesc('created_at')->get();
    // }
    public function find($id)
    {
        return $this->appointment::with([
            'patient',
            'package',
            'specialty',
            'user.role',
            'user.userInfo'
        ])->findOrFail($id);
    }
    // public function findByPatient($id)
    // {
    //     return $this->appointment::with(
    //         ['patient' => function ($query) {
    //             $query->select('id', 'insurance_number', 'identity_card_id', 'status');
    //         }, 'package' => function ($query) {
    //             $query->select('id', 'name', 'description', 'content', 'image', 'slug');
    //         }, 'specialty' => function ($query) {
    //             $query->select('id', 'name', 'description');
    //         }, 'user' => function ($query) {
    //             $query->select('id', 'status', 'role_id');
    //         }, 'user.role' => function ($query) {
    //             $query->select('id', 'name', 'description');
    //         }, 'user.userInfo' => function ($query) {
    //             $query->select('id', 'fullname', 'address', 'avatar', 'phone_number', 'gender', 'dob', 'identity_card_id');
    //         }]
    //     )->where('patient_id', $id)->orderByDesc('created_at')->get();
    // }
    public function findByPatient($id)
    {
        return $this->appointment::with([
            'patient',
            'package',
            'specialty',
            'user.role',
            'user.userInfo'
        ])->where('patient_id', $id)->orderByDesc('created_at')->get();
    }
    public function create($data)
    {
        return $this->appointment::create($data);
    }
    public function update($data, $id)
    {
        $appointment = $this->appointment::findOrFail($id);
        $appointment->update($data);
        return $appointment;
    }
    public function findByDoctor($id)
    {
        return $this->appointment::with([
            'patient',
            'package',
            'specialty',
            'user.role',
            'user.userInfo'
        ])
            ->where('user_id', $id)
            ->where('status', "=", 'confirmed')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function destroy($id)
    {
        return $this->appointment::destroy($id);
    }
    public function statistics()
    {
        $result = $this->appointment::selectRaw("
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

    public function getAppointmentsByStatus()
    {
        $defaultStatus = ['pending', 'confirmed', 'cancelled', 'completed'];
        $data = $this->appointment::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $result = collect($defaultStatus)->map(function ($status) use ($data) {
            return [
                'status' => $status,
                'total' => $data->has($status) ? $data->get($status)->total : 0
            ];
        });
        return $result;
    }
    public function getAppointmentsByMonth($year)
    {
        $data = $this->appointment::select(
            DB::raw('MONTH(appointment_date) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('appointment_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $result = collect(range(1, 12))->map(function ($month) use ($data) {
            return [
                'month' => $month,
                'total' => $data->has($month) ? $data->get($month)->total : 0
            ];
        });
        return $result;
    }

    public function getFrequency()
    {
        $result = $this->appointment::select('patient_id', DB::raw('COUNT(*) as total_appointments'))
            ->where('status', '!=', 'cancelled')
            ->groupBy('patient_id')
            ->having('total_appointments', '>', 1)
            ->orderByDesc('total_appointments')
            ->get();

        return $result;
    }

    public function getTotalPatientFrequency()
    {
        $patients = $this->appointment::select('patient_id')
            ->where('status', '!=', 'cancelled')
            ->groupBy('patient_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        return $patients->count();
    }
}
