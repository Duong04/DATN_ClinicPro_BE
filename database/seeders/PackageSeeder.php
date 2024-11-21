<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            '0fe6c1ec-bc8f-4ca2-8c2e-370f0f036fbc', // Ophthalmology
            '2ea5024e-19fa-4e29-8387-0379e40d20e9', // Dentistry
            '51e7361e-c083-490c-9cea-791f501f5385', // Ear, Nose, and Throat
            'b07bfcc8-b113-4b0b-a712-d191978d3083', // Dermatology
            'bff29dda-c231-4c1a-bd8b-8918a60421f2', // Orthopedics
            'dbb93810-2233-4f9b-a83d-9d2ef5bd88d5', // General Internal Medicine
            'e63bf51b-829f-4fb4-8b28-15f8eb4f6992', // Pediatrics
            'f988dd48-55d2-4d28-b27d-409e8d952a78', // Obstetrics and Gynecology
            '6e9105eb-0daa-4474-8e7e-2d259d54f3e2', // Cardiology
        ];

        $categories = [
            '4a67595d-b66e-4593-8b62-8c26fcd8ef90',
            '954c2a51-2614-4bd0-be02-9a1299a4007c',
            '98937702-9218-4fe8-91f0-72b329d9a27c'
        ];

        
        foreach ($specialties as $specialty_id) {
            for ($i = 1; $i <= 2; $i++) {
                $stt = rand(0, 2);
                $name = 'Package for ' . Str::random(5);
                Package::create([
                    'name' => $name,
                    'description' => 'Description for ' . $name,
                    'content' => 'Detailed content for ' . $name,
                    'image' => 'default.jpg',
                    'slug' => Str::slug($name),
                    'category_id' => $categories[$stt],
                    'specialty_id' => $specialty_id
                ]);
            }
        }
    }
}
