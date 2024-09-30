<?php

namespace App\Services;

use App\Mail\AcceptAppointmentMail;
use App\Models\PatientInfo;
use App\Repositories\Patient\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Appointment\AppointmentRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Exception;

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
        return $this->findAppointment($id);
    }


    public function create($request)
    {
        $dataPatient = $request->validated();
        try {
            $patient = auth()->check() ? auth()->user()->patient : null;
            if (!$patient) {
                $email = $request->input('email');
                $patient = PatientInfo::where('email', $email)->first() ?? $this->createPatient($dataPatient);
            }
            $data = [
                'patient_id' => $patient->id,
                'appointment_date' => $request->input('appointment_date')
            ];
            return $this->appointmentRepository->create($data);
        } catch (Exception $e) {
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
        $appointment = $this->findAppointment($id);
        $this->checkStatus($appointment, 'pending');

        $email = $appointment->patient->patientInfo->email;
        Mail::to($email)->send(new AcceptAppointmentMail($appointment));

        return $this->appointmentRepository->update(['status' => 'confirmed'], $id);
    }
    public function cancel($request, $id)
    {
        return $this->updateStatus($id, 'cancelled', ['cancellation_reason' => $request->input('cancellation_reason')]);
    }

    public function complete($id)
    {
        return $this->updateStatus($id, 'completed');
    }

    private function checkStatus($appointment, $status)
    {
        if ($appointment->status !== $status) {
            throw new Exception("Appointment must be $status to proceed.");
        }
    }

    private function findAppointment($id)
    {
        try {
            return $this->appointmentRepository->find($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Appointment not found');
        }
    }

    public function delete($id)
    {
        $this->findAppointment($id);
        return $this->appointmentRepository->destroy($id);
    }

    private function updateStatus($id, $status, $additionalData = [])
    {
        $appointment = $this->findAppointment($id);
        if ($appointment->status === $status) {
            throw new Exception("Appointment is already $status.");
        }
        $data = array_merge(['status' => $status], $additionalData);

        return $this->appointmentRepository->update($data, $id);
    }
}
