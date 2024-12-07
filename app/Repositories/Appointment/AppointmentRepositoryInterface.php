<?php

namespace App\Repositories\Appointment;

interface AppointmentRepositoryInterface
{
    public function all();
    public function find($id);
    public function findByPatient($id);
    public function create($data);
    public function update($data, $id);
    public function destroy($id);
    public function statistics();
    public function getAppointmentsByStatus();
    public function getAppointmentsByMonth($year);
    public function getFrequency();
    public function getTotalPatientFrequency();
}
