<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách các user có role là patient
        $patientUsers = DB::table('users')
            ->where('RoleID', 'patient')
            ->pluck('UserID')
            ->toArray();

        // Lấy danh sách các bác sĩ
        $doctors = DB::table('doctors')
            ->pluck('DoctorID')
            ->toArray();

        // Lấy danh sách các loại xét nghiệm
        $laboratoryTypes = DB::table('laboratory_types')
            ->select('LaboratoryTypeID', 'Price')
            ->get()
            ->keyBy('LaboratoryTypeID')
            ->toArray();

        $laboratories = [];

        // Tạo 10 xét nghiệm
        for ($i = 0; $i < 10; $i++) {
            $patientId = $patientUsers[array_rand($patientUsers)];
            $doctorId = $doctors[array_rand($doctors)];

            // Chọn một loại xét nghiệm ngẫu nhiên
            $labType = $laboratoryTypes[array_rand($laboratoryTypes)];

            // Tạo ngày và giờ xét nghiệm
            $labDate = now()->subDays(rand(1, 30))->format('Y-m-d');
            $labTime = sprintf('%02d:%02d:00', rand(8, 16), rand(0, 3) * 15);

            $laboratories[] = [
                'LaboratoryTypeID' => $labType->LaboratoryTypeID,
                'LaboratoryDate' => $labDate,
                'LaboratoryTime' => $labTime,
                'UserID' => $patientId,
                'DoctorID' => $doctorId,
                'TotalPrice' => $labType->Price,
                'created_at' => now()->subDays(rand(31, 60)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ];
        }

        DB::table('laboratories')->insert($laboratories);
    }
}