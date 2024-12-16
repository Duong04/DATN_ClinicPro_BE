<?php

namespace App\Services;

use stdClass;
use Carbon\Carbon;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Date;
use App\Repositories\Patient\PatientRepositoryInterface;
use App\Repositories\Appointment\AppointmentRepositoryInterface;
use App\Repositories\PatientInfo\PatientInfoRepositoryInterface;

class StatisticsService
{
    private $patientRepository;
    private $appointmentRepository;

    public function __construct(PatientRepositoryInterface $patientRepository, AppointmentRepositoryInterface $appointmentRepository)
    {
        $this->patientRepository = $patientRepository;
        $this->appointmentRepository = $appointmentRepository;
    }

    // Số bệnh nhân mới theo từng mốc thời gian (ngày, tuần, tháng, năm).
    public function patient()
    {
        try {
            $data = $this->patientRepository->statistics();
            return response()->json([
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // lấy danh sách tổng số lần tái khám theo bệnh nhân
    public function getFrequency()
    {
        try {
            $data = $this->appointmentRepository->getFrequency();
            return response()->json([
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // lấy tổng số bệnh nhân tái khám
    public function getTotalPatientFrequency()
    {
        try {
            $data = $this->appointmentRepository->getTotalPatientFrequency();
            return response()->json([
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    // Số lịch hẹn mới theo từng mốc thời gian (ngày, tuần, tháng, năm).
    public function appointment()
    {
        try {
            $data = $this->appointmentRepository->statistics();
            return response()->json([
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // lấy tổng số lịch hẹn theo status
    public function getAppointmentsByStatus()
    {
        try {
            $data = $this->appointmentRepository->getAppointmentsByStatus();
            $convertedData = new stdClass();

            foreach ($data as $item) {
                $status = $item['status'];
                $convertedData->$status = (int) $item['total'];
            }
            return response()->json([
                'data' => $convertedData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // tổng số lịch hẹn của từng tháng theo năm
    public function getAppointmentsByMonth($year)
    {
        $currentYear = Carbon::now()->year;
        if ($year > $currentYear || $year < 0 || !is_numeric($year) || intval($year) != $year) {
            return response()->json([
                'success' => false,
                'error' => 'Năm không hợp lệ'
            ], 422);
        }

        try {
            $data = $this->appointmentRepository->getAppointmentsByMonth($year);
            $convertedData = new stdClass();

            foreach ($data as $item) {
                $month = $this->change($item['month']);
                $convertedData->$month = (int) $item['total'];
            }
            return response()->json([
                'data' => $convertedData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    private function change($month)
    {
        return 'Tháng ' . $month;
    }
}
