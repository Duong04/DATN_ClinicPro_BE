<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'doctor',
            'description' => 'Bác sĩ',
        ]);

        Role::create([
            'name' => 'patient',
            'description' => 'Bệnh nhân',
        ]);

        Role::create([
            'name' => 'nurse',
            'description' => 'Y tá',
        ]);

        Role::create([
            'name' => 'receptionist',
            'description' => 'Lễ tân',
        ]);

        Role::create([
            'name' => 'accountant',
            'description' => 'Kế toán',
        ]);

        Role::create([
            'name' => 'manager',
            'description' => 'Giám đốc',
        ]);

        Role::create([
            'name' => 'manage',
            'description' => 'Quản lý',
        ]);
    }
}
