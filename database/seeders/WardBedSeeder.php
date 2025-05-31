<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardBedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách các phòng từ bảng wards
        $wards = DB::table('wards')->get();

        $beds = [];

        foreach ($wards as $ward) {
            // Tạo số giường dựa trên capacity của mỗi phòng
            for ($i = 1; $i <= $ward->Capacity; $i++) {
                // Trạng thái giường: 80% available, 10% occupied, 10% maintenance
                $status = 'available';
                $random = rand(1, 10);
                if ($random == 9) {
                    $status = 'occupied';
                } elseif ($random == 10) {
                    $status = 'maintenance';
                }

                $beds[] = [
                    'WardID' => $ward->WardID,
                    'BedNumber' => $i,
                    'Status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('ward_beds')->insert($beds);
    }
}