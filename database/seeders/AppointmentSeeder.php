<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
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

        // Danh sách các lý do khám bệnh
        $reasons = [
            'Khám sức khỏe định kỳ',
            'Đau đầu kéo dài',
            'Ho và sốt',
            'Đau bụng',
            'Đau lưng',
            'Khó thở',
            'Đau họng',
            'Phát ban trên da',
            'Đau khớp',
            'Chóng mặt',
            'Mệt mỏi kéo dài',
            'Kiểm tra sau điều trị',
        ];

        // Danh sách các triệu chứng
        $symptoms = [
            'Sốt cao liên tục',
            'Đau đầu dữ dội',
            'Ho có đờm',
            'Đau bụng vùng thượng vị',
            'Đau lưng vùng thắt lưng',
            'Khó thở khi gắng sức',
            'Đau rát họng',
            'Phát ban đỏ ngứa',
            'Sưng và đau các khớp',
            'Chóng mặt khi thay đổi tư thế',
            'Mệt mỏi, thiếu năng lượng',
            'Đau ngực',
        ];

        // Danh sách các trạng thái cuộc hẹn
        $statuses = [
            'rejected', // từ chối
            'pending',  // chờ xác nhận
            'approved', // đã xác nhận
            'completed' // đã hoàn thành
        ];

        // Danh sách các phương thức thanh toán
        $paymentMethods = [
            'cash', // tiền mặt
            'vnpay', // VNPay
            'zalopay', // ZaloPay
        ];

        // Danh sách các trạng thái thanh toán
        $paymentStatuses = [
            'pending', // chờ thanh toán
            'paid', // đã thanh toán
            'failed', // thanh toán thất bại
        ];

        $appointments = [];

        // Tạo các cuộc hẹn trong quá khứ (đã hoàn thành)
        for ($i = 0; $i < 10; $i++) {
            $patientId = $patientUsers[array_rand($patientUsers)];
            $doctorId = $doctors[array_rand($doctors)];
            $appointmentDate = now()->subDays(rand(1, 30))->format('Y-m-d');
            $appointmentTime = sprintf('%02d:%02d:00', rand(8, 16), rand(0, 3) * 15);

            $appointments[] = [
                'AppointmentDate' => $appointmentDate,
                'AppointmentTime' => $appointmentTime,
                'Reason' => $reasons[array_rand($reasons)],
                'Symptoms' => $symptoms[array_rand($symptoms)],
                'Notes' => 'Đã chuẩn bị đầy đủ giấy tờ và kết quả xét nghiệm trước đó.',
                'UserID' => $patientId,
                'DoctorNotes' => 'Bệnh nhân cần theo dõi thêm và tái khám sau 2 tuần.',
                'DoctorID' => $doctorId,
                'Status' => 'completed',
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => 'paid',
                'payment_id' => 'PAY-' . strtoupper(substr(md5(rand()), 0, 10)),
                'amount' => rand(2, 5) * 100000,
                'payment_details' => json_encode(['transaction_id' => 'TX' . rand(10000, 99999)]),
                'created_at' => now()->subDays(rand(31, 60)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ];
        }

        // Tạo các cuộc hẹn trong tương lai (đã đặt lịch)
        for ($i = 0; $i < 5; $i++) {
            $patientId = $patientUsers[array_rand($patientUsers)];
            $doctorId = $doctors[array_rand($doctors)];
            $appointmentDate = now()->addDays(rand(1, 14))->format('Y-m-d');
            $appointmentTime = sprintf('%02d:%02d:00', rand(8, 16), rand(0, 3) * 15);

            $appointments[] = [
                'AppointmentDate' => $appointmentDate,
                'AppointmentTime' => $appointmentTime,
                'Reason' => $reasons[array_rand($reasons)],
                'Symptoms' => $symptoms[array_rand($symptoms)],
                'Notes' => 'Cần tư vấn chi tiết về tình trạng bệnh.',
                'UserID' => $patientId,
                'DoctorNotes' => null,
                'DoctorID' => $doctorId,
                'Status' => 'approved',
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => rand(0, 1) ? 'pending' : 'paid',
                'payment_id' => rand(0, 1) ? null : 'PAY-' . strtoupper(substr(md5(rand()), 0, 10)),
                'amount' => rand(2, 5) * 100000,
                'payment_details' => null,
                'created_at' => now()->subDays(rand(1, 7)),
                'updated_at' => now()->subDays(rand(0, 1)),
            ];
        }

        DB::table('appointments')->insert($appointments);
    }
}