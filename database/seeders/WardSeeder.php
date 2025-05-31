<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách các WardTypeID từ bảng ward_types
        $wardTypes = DB::table('ward_types')->pluck('WardTypeID')->toArray();

        // Lấy danh sách các DoctorID từ bảng doctors
        $doctors = DB::table('doctors')->pluck('DoctorID')->toArray();

        // Tạo dữ liệu cho các khoa phòng
        $wards = [];

        foreach ($wardTypes as $index => $wardTypeId) {
            // Mỗi loại khoa có thể có 1-2 phòng
            $wardsCount = rand(1, 2);

            for ($i = 1; $i <= $wardsCount; $i++) {
                // Lấy tên khoa từ ward_types
                $wardTypeName = DB::table('ward_types')
                    ->where('WardTypeID', $wardTypeId)
                    ->value('TypeName');

                // Số giường mỗi phòng 10-30
                $capacity = rand(10, 30);

                // Tạo tên phòng dựa theo loại và số thứ tự
                $wardName = $wardTypeName . ' ' . ($i == 1 ? 'A' : 'B');

                // Chọn bác sĩ phụ trách
                $doctorId = $doctors[array_rand($doctors)];

                $wards[] = [
                    'WardTypeID' => $wardTypeId,
                    'WardName' => $wardName,
                    'Capacity' => $capacity,
                    'CurrentOccupancy' => 0, // Ban đầu không có bệnh nhân
                    'DoctorID' => $doctorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Đảm bảo có ít nhất 10 phòng
        while (count($wards) < 10) {
            $wardTypeId = $wardTypes[array_rand($wardTypes)];
            $doctorId = $doctors[array_rand($doctors)];

            // Lấy tên khoa từ ward_types
            $wardTypeName = DB::table('ward_types')
                ->where('WardTypeID', $wardTypeId)
                ->value('TypeName');

            $wards[] = [
                'WardTypeID' => $wardTypeId,
                'WardName' => $wardTypeName . ' C',
                'Capacity' => rand(10, 30),
                'CurrentOccupancy' => 0,
                'DoctorID' => $doctorId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('wards')->insert($wards);
    }
}