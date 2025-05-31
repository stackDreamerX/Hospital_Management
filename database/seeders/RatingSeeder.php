<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingSeeder extends Seeder
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

        // Các đánh giá mẫu
        $feedbacks = [
            'Bác sĩ rất tận tâm và chuyên nghiệp.',
            'Dịch vụ khám chữa bệnh tốt, nhân viên thân thiện.',
            'Thời gian chờ đợi hơi lâu nhưng chất lượng khám tốt.',
            'Bác sĩ giải thích rõ ràng về tình trạng bệnh và phương pháp điều trị.',
            'Cơ sở vật chất sạch sẽ, hiện đại.',
            'Tôi rất hài lòng với dịch vụ tại đây.',
            'Bác sĩ khám rất kỹ và tư vấn chi tiết.',
            'Nhân viên y tế nhiệt tình và chu đáo.',
            'Giá cả hợp lý, chất lượng dịch vụ tốt.',
            'Sẽ quay lại khám lần sau.',
            'Bệnh viện sạch sẽ, quy trình khám bệnh thuận tiện.',
            'Bác sĩ có chuyên môn cao và tận tâm với bệnh nhân.',
        ];

        $ratings = [];

        // Tạo ít nhất 10 đánh giá
        for ($i = 0; $i < 15; $i++) {
            $patientId = $patientUsers[array_rand($patientUsers)];
            $doctorId = $doctors[array_rand($doctors)];

            // Tạo ngẫu nhiên các đánh giá từ 3-5 sao
            $doctorRating = rand(3, 5);
            $serviceRating = rand(3, 5);
            $cleanlinessRating = rand(3, 5);
            $staffRating = rand(3, 5);
            $waitTimeRating = rand(2, 5);

            $ratings[] = [
                'user_id' => $patientId,
                'doctor_id' => $doctorId,
                'appointment_id' => null, // Có thể cập nhật sau khi có dữ liệu appointments
                'doctor_rating' => $doctorRating,
                'service_rating' => $serviceRating,
                'cleanliness_rating' => $cleanlinessRating,
                'staff_rating' => $staffRating,
                'wait_time_rating' => $waitTimeRating,
                'feedback' => $feedbacks[array_rand($feedbacks)],
                'is_anonymous' => rand(0, 1),
                'status' => 'approved',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ];
        }

        DB::table('ratings')->insert($ratings);
    }
}