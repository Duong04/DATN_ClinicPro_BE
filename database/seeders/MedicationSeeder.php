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
        CategoryMedication::create([
            'name' => 'Thuốc điều trị dạ dày, đường ruột',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc giảm đau, hạ sốt',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc điều trị viêm mũi, dị ứng, hô hấp',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc bổ sung vitamin, khoáng chất',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc điều trị ho, cảm cúm',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc điều trị mụn',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc điều trị thần kinh, tuần hoàn máu',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc điều trị đau cơ, xương khớp',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc điều trị tim mạch, huyết áp',
        ]);
        CategoryMedication::create([
            'name' => 'Thuốc điều trị tim mạch, huyết áp',
        ]);

        $data = require(database_path('data/medications.php'));

        for ($i = 0; $i < count($data); $i++) {
            foreach ($data[$i] as $value) {
                Medication::create([
                    'name' => $value,
                    'category_id' => $i + 1
                ]);
            }
        }
    }
}
