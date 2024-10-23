<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use App\Services\MedicationService;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    private $medicationService;
    public function __construct(MedicationService $medicationService)
    {
        $this->medicationService = $medicationService;
    }

    public function index()
    {
        return $this->medicationService->all();
    }
    public function show(Request $request)
    {
        return $this->medicationService->find($request);
    }
}
