<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratoryTypeSeeder extends Seeder
{
    public function run()
    {
        $labTypes = [
            [
                'TypeName' => 'Xét nghiệm máu',
                'Description' => 'Kiểm tra các chỉ số trong máu như: công thức máu, đường huyết, chức năng gan, thận...',
                'Price' => 120000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'Xét nghiệm nước tiểu',
                'Description' => 'Kiểm tra các chỉ số trong nước tiểu để đánh giá chức năng thận và phát hiện bệnh lý',
                'Price' => 90000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'Điện tim đồ (ECG)',
                'Description' => 'Kiểm tra hoạt động điện của tim, phát hiện các bất thường về nhịp tim',
                'Price' => 150000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'Siêu âm',
                'Description' => 'Kiểm tra hình ảnh các cơ quan nội tạng qua sóng siêu âm',
                'Price' => 200000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'X-quang',
                'Description' => 'Chụp X-quang để kiểm tra xương và phổi',
                'Price' => 180000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'CT Scan',
                'Description' => 'Chụp cắt lớp vi tính để kiểm tra chi tiết các cơ quan trong cơ thể',
                'Price' => 1500000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'MRI',
                'Description' => 'Chụp cộng hưởng từ để kiểm tra chi tiết các cơ quan và mô mềm',
                'Price' => 2500000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'Xét nghiệm đờm',
                'Description' => 'Kiểm tra đờm để phát hiện các bệnh lý về đường hô hấp',
                'Price' => 110000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'Xét nghiệm phân',
                'Description' => 'Kiểm tra mẫu phân để phát hiện các bệnh lý về đường tiêu hóa',
                'Price' => 100000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'Nội soi dạ dày',
                'Description' => 'Sử dụng ống nội soi để kiểm tra trực tiếp dạ dày và tá tràng',
                'Price' => 700000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'Nội soi đại tràng',
                'Description' => 'Sử dụng ống nội soi để kiểm tra trực tiếp đại tràng',
                'Price' => 900000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TypeName' => 'Xét nghiệm hormone',
                'Description' => 'Kiểm tra các loại hormone trong cơ thể',
                'Price' => 350000,
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('laboratory_types')->insert($labTypes);
    }
}
