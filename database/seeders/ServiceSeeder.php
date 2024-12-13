<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'service_name' => 'Khám bệnh tổng quát',
                'description' => 'Khám và kiểm tra sức khoẻ tổng quát cho bệnh nhân.',
                'price' => 300000, // Giá khám tổng quát
            ],
            [
                'service_name' => 'Xét nghiệm máu',
                'description' => 'Thực hiện xét nghiệm máu để kiểm tra các chỉ số sức khoẻ.',
                'price' => 200000, // Giá xét nghiệm máu
            ],
            [
                'service_name' => 'Siêu âm bụng',
                'description' => 'Thực hiện siêu âm để kiểm tra các cơ quan nội tạng trong bụng.',
                'price' => 400000, // Giá siêu âm bụng
            ],
            [
                'service_name' => 'Khám mắt',
                'description' => 'Kiểm tra thị lực và khám các bệnh lý về mắt.',
                'price' => 250000, // Giá khám mắt
            ],
            [
                'service_name' => 'Tiêm chủng',
                'description' => 'Dịch vụ tiêm chủng phòng ngừa các bệnh truyền nhiễm.',
                'price' => 150000, // Giá tiêm chủng
            ],
            [
                'service_name' => 'Khám răng miệng',
                'description' => 'Khám và điều trị các vấn đề liên quan đến răng miệng.',
                'price' => 350000, // Giá khám răng miệng
            ],
            [
                'service_name' => 'Điều trị cảm cúm',
                'description' => 'Khám và điều trị các triệu chứng cảm cúm, sốt, ho.',
                'price' => 150000, // Giá điều trị cảm cúm
            ],
            [
                'service_name' => 'Phẫu thuật nhỏ',
                'description' => 'Các phẫu thuật nhỏ như cắt mụn, u bướu không cần nhập viện.',
                'price' => 1000000, // Giá phẫu thuật nhỏ
            ],
            [
                'service_name' => 'Khám phụ khoa',
                'description' => 'Khám sức khoẻ phụ khoa định kỳ cho nữ giới.',
                'price' => 400000, // Giá khám phụ khoa
            ],
            [
                'service_name' => 'Khám tim mạch',
                'description' => 'Khám và kiểm tra các vấn đề về tim mạch.',
                'price' => 500000, // Giá khám tim mạch
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

    }
}
