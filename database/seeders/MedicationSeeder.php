<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;
use App\Models\CategoryMedication;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $medicationCategory = [
            'Thuốc điều trị dạ dày, đường ruột',
            'Thuốc giảm đau, hạ sốt',
            'Thuốc điều trị viêm mũi, dị ứng, hô hấp',
            'Thuốc bổ sung vitamin, khoáng chất',
            'Thuốc điều trị ho, cảm cúm',
            'Thuốc điều trị thần kinh, tuần hoàn máu',
            'Thuốc điều trị đau cơ, xương khớp',
            'Thuốc điều trị tim mạch, huyết áp',
            'Thuốc điều trị tiểu đường'
        ];

        foreach ($medicationCategory as $index => $name) {
            CategoryMedication::create([
                'name' => $name,
                'order' => $index + 1,
            ]);
        }

        $data = require(database_path('data/medications.php'));

        $categories = CategoryMedication::orderBy('order')->get();

        foreach ($categories as $index => $category) {
            if (isset($data[$index])) {
                foreach ($data[$index] as $medicationName) {
                    Medication::create([
                        'name' => $medicationName,
                        'category_id' => $category->id
                    ]);
                }
            }
        }
    }
}
