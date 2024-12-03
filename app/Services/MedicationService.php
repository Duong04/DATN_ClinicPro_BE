<?php

namespace App\Services;

use App\Models\Medication;
use App\Models\CategoryMedication;

class MedicationService
{
    public function all()
    {
        $data = CategoryMedication::all();
        return response()->json(['data' => $data]);
    }
    public function find($request)
    {
        $category_id = $request->query('category_id');
        if ($category_id) {
            $data = Medication::where('category_id', $category_id)->get();
            return response()->json(['data' => $data]);
        } else {
            return response()->json(['message' => 'ID danh mục là bắt buộc'], 400);
        }
    }
}
