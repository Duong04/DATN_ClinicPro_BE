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
        $data = require(database_path('data/medications.php'));

        $ids = [
            '091684d8-6250-4624-a1e2-1df20636c1dd',
            '0a793352-0bfd-47c3-b268-8e6854a060f6',
            '1c54a5ac-4d88-4458-925a-a2cdf4ba1875',
            '243dfa5b-3fcc-44de-8967-a1b196b5c650',
            '288d7448-b116-486e-8541-a2c8003194ec',
            '3611025c-c0b3-4b6a-89d4-94a26ec4a5ee',
            '3b38dd04-e305-4eab-b78d-23bbf25db6ad',
            '40ec8d8d-ac9a-46be-b194-bfc0b7e4a371',
            '6db7faa3-2921-4f44-9ee0-67c9393b599f',
            '7398b03b-7c87-401a-8e84-91ed545aff9f',
            '7ad3648b-0736-4f15-91ce-598a3b7bb29c',
            '88812daf-5631-4219-8f17-56b9587b0b1b',
            '9b24cb57-3f90-402a-a218-03c3d76c4ce6',
            '9fc596d6-cb1e-4d88-8697-e587f1dfd471',
            'b455cb1b-5bfb-44c9-96e9-791163f14ea3',
            'c5f3bd75-1f8c-46ff-b50c-d4f455fe70db',
            'd695a1b7-755b-4ff5-abee-3d49f9c155ed',
            'e0adaacf-9670-4f99-b52f-0c0c4f7c4c55',
            'e8e42c27-a30a-4a7b-8ad3-6293db5dfa39',
            'f38ae46d-14c7-48ca-b90d-c4650cc02201'
        ];
        for ($i = 0; $i < count($data); $i++) {
            foreach ($data[$i] as $value) {
                Medication::create([
                    'name' => $value,
                    'category_id' => $ids[$i]
                ]);
            }
        }
    }
}
