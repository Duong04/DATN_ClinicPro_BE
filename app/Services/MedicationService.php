<?php

namespace App\Services;

use App\Models\Medication;
use App\Models\CategoryMedication;

class MedicationService
{
    public function all()
    {
        $data = CategoryMedication::all();
        return response()->json(
            ['data' => $data],
            200
        );
    }
    public function find($request)
    {
        $category_id = $request->query('category_id');
        $data = Medication::where('category_id', $category_id)->get();
        return response()->json(
            ['data' => $data],
            200
        );
    }
}
