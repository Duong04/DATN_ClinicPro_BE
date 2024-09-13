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
            'type_id' => 1
        ]);

        Role::create([
            'name' => 'patient',
            'description' => 'Bệnh nhân',
            'type_id' => 2
        ]);

        Role::create([
            'name' => 'nurse',
            'description' => 'Y tá',
            'type_id' => 1
        ]);

        Role::create([
            'name' => 'receptionist',
            'description' => 'Lễ tân',
            'type_id' => 1
        ]);

        Role::create([
            'name' => 'accountant',
            'description' => 'Kế toán',
            'type_id' => 1
        ]);

        Role::create([
            'name' => 'manager',
            'description' => 'Giám đốc',
            'type_id' => 1
        ]);

        Role::create([
            'name' => 'manage',
            'description' => 'Quản lý',
            'type_id' => 1
        ]);
    }
}
