<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách các bác sĩ
        $doctors = DB::table('doctors')->pluck('DoctorID')->toArray();

        $schedules = [];

        // Tạo lịch làm việc cho mỗi bác sĩ
        foreach ($doctors as $doctorId) {
            // Mỗi bác sĩ làm việc từ 3-6 ngày trong tuần
            $workDays = rand(3, 6);

            // Chọn ngẫu nhiên các ngày trong tuần (1 = Thứ 2, 7 = Chủ nhật)
            $daysOfWeek = array_rand(array_flip([1, 2, 3, 4, 5, 6, 7]), $workDays);
            if (!is_array($daysOfWeek)) {
                $daysOfWeek = [$daysOfWeek];
            }

            foreach ($daysOfWeek as $day) {
                // Tạo các ca làm việc khác nhau
                if ($day == 6 || $day == 7) {
                    // Thứ 7, Chủ nhật: ca sáng
                    $schedules[] = [
                        'doctor_id' => $doctorId,
                        'day_of_week' => $day,
                        'start_time' => '08:00:00',
                        'end_time' => '12:00:00',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    // Ngày thường: ca sáng và chiều
                    $schedules[] = [
                        'doctor_id' => $doctorId,
                        'day_of_week' => $day,
                        'start_time' => '08:00:00',
                        'end_time' => '12:00:00',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // 70% bác sĩ làm cả ca chiều
                    if (rand(1, 10) <= 7) {
                        $schedules[] = [
                            'doctor_id' => $doctorId,
                            'day_of_week' => $day,
                            'start_time' => '13:30:00',
                            'end_time' => '17:00:00',
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        DB::table('doctor_schedules')->insert($schedules);
    }
}