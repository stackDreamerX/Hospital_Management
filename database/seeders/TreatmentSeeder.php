<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreatmentSeeder extends Seeder
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

        // Lấy danh sách các loại điều trị
        $treatmentTypes = DB::table('treatment_types')
            ->pluck('TreatmentTypeID')
            ->toArray();

        // Danh sách các ghi chú mẫu
        $notes = [
            'Bệnh nhân cần theo dõi thêm sau điều trị.',
            'Điều trị đạt kết quả tốt, cần tái khám sau 2 tuần.',
            'Bệnh nhân đáp ứng tốt với phương pháp điều trị.',
            'Cần theo dõi tác dụng phụ của thuốc trong quá trình điều trị.',
            'Bệnh nhân cần nghỉ ngơi và hạn chế vận động mạnh.',
            'Điều trị đang tiến triển tốt, tiếp tục phác đồ hiện tại.',
            'Cần điều chỉnh liều lượng thuốc trong đợt điều trị tiếp theo.',
            'Bệnh nhân cần tuân thủ chế độ ăn uống đã hướng dẫn.',
            'Cần thực hiện thêm xét nghiệm để đánh giá hiệu quả điều trị.',
            'Bệnh nhân cần tiếp tục tập vật lý trị liệu tại nhà.',
        ];

        // Danh sách các trạng thái điều trị
        $statuses = [
            'pending',    // chờ điều trị
            'in_progress', // đang điều trị
            'completed',   // đã hoàn thành
            'cancelled',   // đã hủy
        ];

        $treatments = [];

        // Tạo các điều trị trong quá khứ (đã hoàn thành)
        for ($i = 0; $i < 7; $i++) {
            $patientId = $patientUsers[array_rand($patientUsers)];
            $doctorId = $doctors[array_rand($doctors)];
            $treatmentTypeId = $treatmentTypes[array_rand($treatmentTypes)];
            $treatmentDate = now()->subDays(rand(1, 60))->format('Y-m-d');
            $duration = rand(1, 30) . ' ngày'; // Thời gian điều trị

            $treatments[] = [
                'TreatmentTypeID' => $treatmentTypeId,
                'TreatmentDate' => $treatmentDate,
                'UserID' => $patientId,
                'DoctorID' => $doctorId,
                'Duration' => $duration,
                'Notes' => $notes[array_rand($notes)],
                'Status' => 'completed',
                'TotalPrice' => rand(5, 50) * 100000, // Giá từ 500.000đ đến 5.000.000đ
                'created_at' => now()->subDays(rand(61, 90)),
                'updated_at' => now()->subDays(rand(1, 60)),
            ];
        }

        // Tạo các điều trị đang tiến hành
        for ($i = 0; $i < 3; $i++) {
            $patientId = $patientUsers[array_rand($patientUsers)];
            $doctorId = $doctors[array_rand($doctors)];
            $treatmentTypeId = $treatmentTypes[array_rand($treatmentTypes)];
            $treatmentDate = now()->subDays(rand(1, 15))->format('Y-m-d');
            $duration = rand(15, 60) . ' ngày'; // Thời gian điều trị dài hơn

            $treatments[] = [
                'TreatmentTypeID' => $treatmentTypeId,
                'TreatmentDate' => $treatmentDate,
                'UserID' => $patientId,
                'DoctorID' => $doctorId,
                'Duration' => $duration,
                'Notes' => $notes[array_rand($notes)],
                'Status' => 'in_progress',
                'TotalPrice' => rand(10, 100) * 100000, // Giá từ 1.000.000đ đến 10.000.000đ
                'created_at' => now()->subDays(rand(16, 30)),
                'updated_at' => now()->subDays(rand(1, 15)),
            ];
        }

        // Tạo các điều trị chờ thực hiện
        for ($i = 0; $i < 2; $i++) {
            $patientId = $patientUsers[array_rand($patientUsers)];
            $doctorId = $doctors[array_rand($doctors)];
            $treatmentTypeId = $treatmentTypes[array_rand($treatmentTypes)];
            $treatmentDate = now()->addDays(rand(1, 10))->format('Y-m-d');
            $duration = rand(7, 30) . ' ngày';

            $treatments[] = [
                'TreatmentTypeID' => $treatmentTypeId,
                'TreatmentDate' => $treatmentDate,
                'UserID' => $patientId,
                'DoctorID' => $doctorId,
                'Duration' => $duration,
                'Notes' => 'Chuẩn bị cho liệu trình điều trị.',
                'Status' => 'pending',
                'TotalPrice' => rand(5, 50) * 100000,
                'created_at' => now()->subDays(rand(1, 5)),
                'updated_at' => now(),
            ];
        }

        DB::table('treatments')->insert($treatments);
    }
}