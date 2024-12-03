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

    public function destroy($id)
    {
        return $this->appointment::destroy($id);
    }
}
