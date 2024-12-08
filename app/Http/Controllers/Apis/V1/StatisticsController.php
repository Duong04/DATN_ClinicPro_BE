<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    private $statisticsService;
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function patient()
    {
        return $this->statisticsService->patient();
    }
    public function patientTotal()
    {
        return $this->statisticsService->patientTotal();
    }
    public function getFrequency()
    {
        return $this->statisticsService->getFrequency();
    }

    public function getTotalPatientFrequency()
    {
        return $this->statisticsService->getTotalPatientFrequency();
    }
    public function appointment()
    {
        return $this->statisticsService->appointment();
    }
    public function appointmentTotal()
    {
        return $this->statisticsService->appointmentTotal();
    }
    public function getAppointmentsByStatus()
    {
        return $this->statisticsService->getAppointmentsByStatus();
    }

    public function getAppointmentsByMonth($year)
    {
        return $this->statisticsService->getAppointmentsByMonth($year);
    }
}
