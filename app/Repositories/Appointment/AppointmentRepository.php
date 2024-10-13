<?php

namespace App\Repositories\Appointment;

use App\Models\Appointment;

class AppointmentRepository implements AppointmentRepositoryInterface
{


    private $appointment;
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function all()
    {
        return $this->appointment::all();
    }
    public function find($id)
    {
        return $this->appointment::findOrFail($id);
    }
    public function findByPatient($id)
    {
        return $this->appointment::where('patient_id', $id)->get();
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

    public function destroy($id)
    {
        return $this->appointment::destroy($id);
    }
}
