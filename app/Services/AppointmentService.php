<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Doctor;
use App\Models\PatientInfo;
use App\Mail\SendAppointment;
use App\Mail\AcceptAppointmentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AppointmentResource;
use App\Repositories\Patient\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Appointment\AppointmentRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;

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
        return AppointmentResource::collection($this->appointmentRepository->all());
    }

    public function show($id)
    {
        $appointment = $this->findAppointment($id);
        return new AppointmentResource($appointment);
    }

    public function findByIdPatient($id)
    {
        $this->findPatient($id);
        return AppointmentResource::collection($this->appointmentRepository->findByPatient($id));
    }
    public function findByDoctor($id)
    {
        $this->findDoctor($id);
        return AppointmentResource::collection($this->appointmentRepository->findByDoctor($id));
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
                'description' => $request->input('description'),
                'package_id' => $request->input('package_id'),
                'specialty_id' => $request->input('specialty_id'),
                'patient_id' => $patient->patient_id ?? $patient->id,
                'appointment_date' => $request->input('appointment_date')
            ];

            $appointment = $this->appointmentRepository->create($data);
            Mail::to($appointment->patient->patientInfo->email)->send(new SendAppointment($appointment));

            return $appointment;
        } catch (Exception $e) {
            return [
                'error' => 'Failed to create appointment: ' . $e->getMessage()
            ];
        }
    }
    public function assign($id, $request)
    {
        $appointment = $this->findAppointment($id);
        $this->checkStatus($appointment, 'confirmed');

        $rules = [
            'user_id' => "exists:users,id"
        ];
        $messages = [
            'exists' => 'Giá trị của :attribute không tồn tại!',
        ];
        $attributes = [
            'user_id' => 'ID Bác sĩ'
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            throw new Exception($validator->errors());
        }
        $validator->validated();
        $data['user_id'] = $request->input('user_id');
        return $this->appointmentRepository->update($data, $id);
    }
    private function createPatient($dataPatient)
    {
        $data = [
            "fullname" => $dataPatient['fullname'],
            "email" => $dataPatient['email'],
            "phone_number" => $dataPatient['phone_number'],
            "address" => $dataPatient['address'],
            "gender" => $dataPatient['gender'],
            "dob" => $dataPatient['dob']
        ];
        $patient = $this->patientRepository->create($data);
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
        return $this->updateStatus($id, 'cancelled', ['completed', 'cancelled'], '', ['cancellation_reason' => $request->input('cancellation_reason')]);
    }

    public function complete($id)
    {
        return $this->updateStatus($id, 'completed', ['completed', 'cancelled'], true);
    }

    private function checkStatus($appointment, $status)
    {
        if ($appointment->status !== $status) {
            throw new \InvalidArgumentException('Trạng thái lịch hẹn không hợp lệ!');
        }
    }

    private function findAppointment($id)
    {
        try {
            return $this->appointmentRepository->find($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Lịch hẹn không tồn tại');
        }
    }

    public function delete($id)
    {
        $this->findAppointment($id);
        return $this->appointmentRepository->destroy($id);
    }

    private function updateStatus($id, $status, array $statusCheck, $isCompleted = false, $additionalData = [])
    {
        $appointment = $this->findAppointment($id);

        if (in_array($appointment->status, $statusCheck)) {
            throw new \InvalidArgumentException('Trạng thái lịch hẹn không hợp lệ!');
        }

        if ($isCompleted && $appointment->status !== 'confirmed') {
            throw new Exception("Trạng thái lịch hẹn không hợp lệ!");
        }
        $data = array_merge(['status' => $status], $additionalData);

        return $this->appointmentRepository->update($data, $id);
    }

    private function findPatient($id)
    {
        try {
            return $this->patientRepository->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Bệnh nhân không tồn tại');
        }
    }
    private function findDoctor($id)
    {
        try {
            return User::findOrFail($id);
        } catch (ModelNotFoundException $th) {
            throw new Exception('Bác sĩ không tồn tại');
        }
    }
}
