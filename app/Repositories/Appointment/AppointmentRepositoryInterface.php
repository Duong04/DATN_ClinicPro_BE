<?php

namespace App\Repositories\Appointment;

interface AppointmentRepositoryInterface
{
    public function all();
    public function find($id);
    public function create($data);
    public function cancel($data, $id);
    public function update($data, $id);
    public function destroy($id);
}
