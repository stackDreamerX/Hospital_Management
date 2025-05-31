<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users with RoleID = 'doctor'
        $doctorUsers = DB::table('users')
            ->where('RoleID', 'doctor')
            ->get();

        $specialities = [
            'Nội Tổng Quát',
            'Ngoại Tổng Quát',
            'Nhi Khoa',
            'Sản Phụ Khoa',
            'Tim Mạch',
            'Thần Kinh',
            'Da Liễu',
            'Tai Mũi Họng',
            'Mắt',
            'Cơ Xương Khớp',
            'Tiêu Hóa',
            'Hô Hấp',
            'Thận - Tiết Niệu',
            'Nội Tiết',
        ];

        $titles = [
            'Bác sĩ',
            'Bác sĩ Chuyên khoa I',
            'Bác sĩ Chuyên khoa II',
            'Thạc sĩ, Bác sĩ',
            'Tiến sĩ, Bác sĩ',
            'Phó Giáo sư, Tiến sĩ',
            'Giáo sư, Tiến sĩ',
        ];

        $locations = [
            'Khu A - Tầng 1',
            'Khu A - Tầng 2',
            'Khu A - Tầng 3',
            'Khu B - Tầng 1',
            'Khu B - Tầng 2',
            'Khu C - Tầng 1',
            'Phòng khám đa khoa',
        ];

        $doctors = [];

        foreach ($doctorUsers as $index => $user) {
            $doctors[] = [
                'UserID' => $user->UserID,
                'Speciality' => $specialities[$index % count($specialities)],
                'Title' => $titles[$index % count($titles)],
                'WorkLocation' => $locations[$index % count($locations)],
                'AvailableHours' => 'Thứ 2-6: 8:00-17:00, Thứ 7: 8:00-12:00',
                'pricing_vn' => ($index % 3 + 1) * 100000, // Giá khám cho người Việt (100k-300k VND)
                'pricing_foreign' => ($index % 3 + 2) * 10, // Giá khám cho người nước ngoài (USD)
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Thêm một số bác sĩ khác nếu số lượng chưa đủ 10
        $specialtyIndex = 0;
        while (count($doctors) < 10) {
            // Tạo thêm user bác sĩ
            $userId = DB::table('users')->insertGetId([
                'RoleID' => 'doctor',
                'username' => 'bsdoctor' . (count($doctors) + 1),
                'FullName' => 'Bác sĩ Việt Nam ' . (count($doctors) + 1),
                'Email' => 'doctor' . (count($doctors) + 1) . '@benhvien.vn',
                'password' => bcrypt('password'),
                'PhoneNumber' => '09' . rand(10000000, 99999999),
                'DateOfBirth' => rand(1970, 1990) . '-' . rand(1, 12) . '-' . rand(1, 28),
                'Gender' => (count($doctors) % 2 == 0) ? 'Male' : 'Female',
                'Address' => 'Quận ' . rand(1, 12) . ', TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $doctors[] = [
                'UserID' => $userId,
                'Speciality' => $specialities[$specialtyIndex % count($specialities)],
                'Title' => $titles[rand(0, count($titles) - 1)],
                'WorkLocation' => $locations[rand(0, count($locations) - 1)],
                'AvailableHours' => 'Thứ 2-6: 8:00-17:00, Thứ 7: 8:00-12:00',
                'pricing_vn' => (rand(1, 3) + 1) * 100000, // Giá khám cho người Việt (200k-400k VND)
                'pricing_foreign' => (rand(1, 3) + 2) * 10, // Giá khám cho người nước ngoài (USD)
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $specialtyIndex++;
        }

        DB::table('doctors')->insert($doctors);
    }
}