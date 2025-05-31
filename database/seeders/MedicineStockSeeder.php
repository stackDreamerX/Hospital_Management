<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineStockSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách ID thuốc từ bảng medicines
        $medicineIds = DB::table('medicines')->pluck('MedicineID')->toArray();

        // Danh sách nhà cung cấp
        $providers = [
            'Công ty Dược phẩm Hà Nội',
            'Công ty CP Dược phẩm Imexpharm',
            'Công ty Dược phẩm Thái Minh',
            'Công ty Dược phẩm Traphaco',
            'Công ty CP Dược Hậu Giang',
            'Công ty CP Dược OPC',
            'Viện Dược liệu Việt Nam',
            'Công ty Dược phẩm Nam Hà',
            'Công ty Rohto-Mentholatum Việt Nam',
            'Công ty Dược phẩm Sài Gòn',
        ];

        $stocks = [];

        // Tạo dữ liệu cho mỗi thuốc
        foreach ($medicineIds as $medicineId) {
            // Mỗi thuốc có thể có nhiều lô với số lượng khác nhau
            $batches = rand(1, 3); // Mỗi thuốc có 1-3 bản ghi

            for ($i = 0; $i < $batches; $i++) {
                $stocks[] = [
                    'MedicineID' => $medicineId,
                    'QuantityInStock' => rand(50, 500),
                    'Provider' => $providers[array_rand($providers)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Thêm một số bản ghi khác để đảm bảo có ít nhất 10 bản ghi
        while (count($stocks) < 10) {
            $medicineId = $medicineIds[array_rand($medicineIds)];
            $stocks[] = [
                'MedicineID' => $medicineId,
                'QuantityInStock' => rand(50, 500),
                'Provider' => $providers[array_rand($providers)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('medicine_stock')->insert($stocks);
    }
}
