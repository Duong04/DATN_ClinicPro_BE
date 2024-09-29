<?php

namespace App\Services;

use App\Mail\AcceptAppointmentMail;
use App\Repositories\Patient\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Appointment\AppointmentRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class AppointmentService
{

    private $appointmentRepository;
    private $patientInfoRepository;
    private $patientRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository, PatientRepositoryInterface $patientRepository, PatientInfoRepositoryInterface $patientInfoRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->patientInfoRepository = $patientInfoRepository;
        $this->patientRepository = $patientRepository;
    }

    public function all()
    {
        return $this->appointmentRepository->all();
    }

    public function show($id)
    {
        try {
            return $this->appointmentRepository->find($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Appointment not found');
        }
    }


    public function create($request)
    {
        $dataPatient = $request->validated();
        try {
            $patient = auth()->check()
                ? auth()->user()->patient
                : $this->createPatient($dataPatient);
            $data = [
                'patient_id' => $patient->id,
                'appointment_date' => $request->input('appointment_date')
            ];
            return $this->appointmentRepository->create($data);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Unable to find necessary resources for appointment');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create appointment: ' . $e->getMessage()], 500);
        }
    }

    private function createPatient($dataPatient)
    {
        $patient = $this->patientRepository->create([]);
        $dataPatient['patient_id'] = $patient->id;

        $this->patientInfoRepository->create($dataPatient);
        return $patient;
    }

    public function update($id)
    {
        try {
            $appointment = $this->appointmentRepository->find($id);
            if ($appointment->status !== 'pending') {
                return [
                    'error' => true,
                    'message' => 'Appointment is not pending and cannot be updated.'
                ];
            }
            $email = $appointment->patient->patientInfo->email;
            Mail::to($email)->send(new AcceptAppointmentMail($appointment));
            $data['status'] = 'confirmed';
            return $this->appointmentRepository->update($data, $id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Appointment not found');
        }
    }
    public function cancel($request, $id)
    {
        try {
            $data['status'] = 'cancelled';
            $data['cancellation_reason'] = $request->input('cancellation_reason');
            return $this->appointmentRepository->cancel($data, $id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Appointment not found');
        }
    }

    public function delete($id)
    {
        try {
            return $this->appointmentRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Appointment not found');
        }
    }
}
