<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specialty::create([
            'name' => 'General Internal Medicine',
            'description' => 'Nội tổng quát',
        ]);

        Specialty::create([
            'name' => 'Pediatrics',
            'description' => 'Chuyên khoa Nhi',
        ]);

        Specialty::create([
            'name' => 'Dermatology',
            'description' => 'Chuyên khoa Da liễu',
        ]);

        Specialty::create([
            'name' => 'Obstetrics and Gynecology',
            'description' => 'Sản phụ khoa',
        ]);

        Specialty::create([
            'name' => 'Ophthalmology',
            'description' => 'Chuyên khoa Mắt',
        ]);

        Specialty::create([
            'name' => 'Ear, Nose, and Throat',
            'description' => 'Tai Mũi Họng',
        ]);

        Specialty::create([
            'name' => 'Dentistry',
            'description' => 'Răng Hàm Mặt',
        ]);

        Specialty::create([
            'name' => 'Orthopedics',
            'description' => 'Chỉnh hình Cơ xương khớp',
        ]);
    }
}
