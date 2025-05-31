<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrescriptionSeeder extends Seeder
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

        // Danh sách các chẩn đoán mẫu
        $diagnoses = [
            'Viêm họng cấp',
            'Viêm phế quản',
            'Viêm xoang',
            'Viêm dạ dày',
            'Đau đầu căng thẳng',
            'Viêm da dị ứng',
            'Tăng huyết áp',
            'Đau lưng cấp tính',
            'Viêm khớp',
            'Cảm cúm',
            'Tiêu chảy cấp',
            'Viêm tai giữa',
        ];

        // Danh sách các kết quả xét nghiệm mẫu
        $testResults = [
            'Công thức máu trong giới hạn bình thường',
            'Chức năng gan, thận bình thường',
            'CRP tăng nhẹ',
            'Đường huyết: 5.2 mmol/L',
            'Cholesterol: 5.4 mmol/L',
            'Xét nghiệm nước tiểu bình thường',
            'Không phát hiện bất thường trong xét nghiệm',
            'Chỉ số viêm tăng nhẹ',
            'Xét nghiệm vi khuẩn âm tính',
            'Chỉ số đông máu trong giới hạn bình thường',
        ];

        // Danh sách các hướng dẫn mẫu
        $instructions = [
            'Uống thuốc đúng liều lượng sau bữa ăn',
            'Nghỉ ngơi, uống nhiều nước, tránh thức ăn cay nóng',
            'Uống thuốc đều đặn, tái khám sau 1 tuần',
            'Theo dõi tác dụng phụ của thuốc, ngưng thuốc nếu có dấu hiệu dị ứng',
            'Tránh vận động mạnh trong thời gian điều trị',
            'Giữ vết thương sạch sẽ, thay băng mỗi ngày',
            'Kiêng ăn đồ chua, cay, nóng trong thời gian điều trị',
            'Tái khám ngay nếu triệu chứng nặng hơn',
            'Uống thuốc trước bữa ăn 30 phút',
            'Theo dõi huyết áp mỗi ngày và ghi lại',
        ];

        $prescriptions = [];

        // Tạo 10 đơn thuốc
        for ($i = 0; $i < 10; $i++) {
            $patientId = $patientUsers[array_rand($patientUsers)];
            $doctorId = $doctors[array_rand($doctors)];
            $prescriptionDate = now()->subDays(rand(1, 30))->format('Y-m-d H:i:s');

            // Tạo các chỉ số sinh hiệu ngẫu nhiên
            $bloodPressure = rand(100, 140) . '/' . rand(60, 90) . ' mmHg';
            $heartRate = rand(60, 100); // Chỉ lưu giá trị số nguyên
            $temperature = rand(365, 375) / 10 . ' °C';
            $spO2 = rand(95, 100);

            $prescriptions[] = [
                'PrescriptionDate' => $prescriptionDate,
                'UserID' => $patientId,
                'DoctorID' => $doctorId,
                'TotalPrice' => 0, // Sẽ cập nhật sau khi thêm chi tiết đơn thuốc
                'Diagnosis' => $diagnoses[array_rand($diagnoses)],
                'TestResults' => $testResults[array_rand($testResults)],
                'BloodPressure' => $bloodPressure,
                'HeartRate' => $heartRate,
                'Temperature' => $temperature,
                'SpO2' => $spO2,
                'Instructions' => $instructions[array_rand($instructions)],
                'created_at' => now()->subDays(rand(31, 60)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ];
        }

        DB::table('prescriptions')->insert($prescriptions);
    }
}