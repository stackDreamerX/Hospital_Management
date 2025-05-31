<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreatmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treatmentTypes = [
            [
                'TreatmentTypeName' => 'Khám Ngoại Trú',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Điều Trị Nội Trú',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Phẫu Thuật Nhỏ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Phẫu Thuật Lớn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Vật Lý Trị Liệu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Châm Cứu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Xạ Trị',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Hóa Trị',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Thẩm Tách Máu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Liệu Pháp Tâm Lý',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Y Học Cổ Truyền',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TreatmentTypeName' => 'Chăm Sóc Giảm Nhẹ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('treatment_types')->insert($treatmentTypes);
    }
}