<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorTimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách các bác sĩ
        $doctors = DB::table('doctors')->pluck('DoctorID')->toArray();

        // Lấy danh sách các lịch làm việc của bác sĩ
        $schedules = DB::table('doctor_schedules')->get();

        // Lấy danh sách các cuộc hẹn đã được đặt
        $appointments = DB::table('appointments')->get();

        $timeSlots = [];

        // Tạo các slot thời gian cho 14 ngày tới
        for ($dayOffset = 0; $dayOffset < 14; $dayOffset++) {
            $date = now()->addDays($dayOffset)->format('Y-m-d');
            $dayOfWeek = now()->addDays($dayOffset)->dayOfWeek;

            // Chuyển đổi định dạng ngày trong tuần từ Carbon (0 = Chủ nhật) sang định dạng của chúng ta (7 = Chủ nhật)
            $dayOfWeek = ($dayOfWeek == 0) ? 7 : $dayOfWeek;

            // Lọc lịch làm việc cho ngày này
            $daySchedules = $schedules->where('day_of_week', $dayOfWeek);

            foreach ($daySchedules as $schedule) {
                // Tạo các slot 30 phút
                $startTime = strtotime($schedule->start_time);
                $endTime = strtotime($schedule->end_time);

                for ($time = $startTime; $time < $endTime; $time += 30 * 60) {
                    $slotTime = date('H:i:s', $time);

                    // Kiểm tra xem slot này đã được đặt lịch chưa
                    $isBooked = false;
                    $appointmentId = null;

                    foreach ($appointments as $appointment) {
                        if ($appointment->DoctorID == $schedule->doctor_id &&
                            $appointment->AppointmentDate == $date &&
                            substr($appointment->AppointmentTime, 0, 5) == substr($slotTime, 0, 5)) {
                            $isBooked = true;
                            $appointmentId = $appointment->AppointmentID;
                            break;
                        }
                    }

                    // Xác định trạng thái của slot
                    $status = 'available';
                    if ($isBooked) {
                        // Nếu ngày đã qua, đánh dấu là đã hoàn thành
                        if (strtotime($date) < strtotime(date('Y-m-d'))) {
                            $status = 'completed';
                        } else {
                            $status = 'booked';
                        }
                    }

                    $timeSlots[] = [
                        'doctor_id' => $schedule->doctor_id,
                        'date' => $date,
                        'time' => $slotTime,
                        'status' => $status,
                        'appointment_id' => $appointmentId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Đảm bảo có ít nhất 10 time slots
        if (count($timeSlots) < 10) {
            $additionalNeeded = 10 - count($timeSlots);

            for ($i = 0; $i < $additionalNeeded; $i++) {
                $doctorId = $doctors[array_rand($doctors)];
                $date = now()->addDays(rand(1, 14))->format('Y-m-d');
                $hour = rand(8, 16);
                $minute = rand(0, 1) * 30;
                $time = sprintf('%02d:%02d:00', $hour, $minute);

                $timeSlots[] = [
                    'doctor_id' => $doctorId,
                    'date' => $date,
                    'time' => $time,
                    'status' => 'available',
                    'appointment_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Chia nhỏ mảng để tránh lỗi khi insert quá nhiều dữ liệu cùng lúc
        $chunks = array_chunk($timeSlots, 100);
        foreach ($chunks as $chunk) {
            DB::table('doctor_time_slots')->insert($chunk);
        }
    }
}