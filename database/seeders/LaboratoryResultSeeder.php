<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratoryResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách các xét nghiệm
        $laboratories = DB::table('laboratories')->get();

        // Các kết quả xét nghiệm mẫu theo loại xét nghiệm
        $resultsByType = [
            // Xét nghiệm máu
            1 => [
                'Hồng cầu: 4.8 triệu/mm³ (Bình thường: 4.2-5.4 triệu/mm³)',
                'Bạch cầu: 7.500/mm³ (Bình thường: 4.000-10.000/mm³)',
                'Tiểu cầu: 250.000/mm³ (Bình thường: 150.000-400.000/mm³)',
                'Hemoglobin: 14.5 g/dL (Bình thường: 12-16 g/dL)',
                'Hematocrit: 42% (Bình thường: 37-47%)',
            ],
            // Xét nghiệm nước tiểu
            2 => [
                'Màu sắc: Vàng nhạt (Bình thường)',
                'Độ trong: Trong (Bình thường)',
                'pH: 6.0 (Bình thường: 4.5-8.0)',
                'Protein: Âm tính (Bình thường)',
                'Glucose: Âm tính (Bình thường)',
            ],
            // Điện tim đồ (ECG)
            3 => [
                'Nhịp tim: 72 lần/phút (Bình thường: 60-100 lần/phút)',
                'Trục điện tim: Bình thường',
                'Khoảng PR: 0.16 giây (Bình thường: 0.12-0.20 giây)',
                'Khoảng QRS: 0.08 giây (Bình thường: <0.12 giây)',
                'Không phát hiện rối loạn nhịp tim',
            ],
            // Siêu âm
            4 => [
                'Gan: Kích thước và cấu trúc bình thường',
                'Túi mật: Không có sỏi, thành không dày',
                'Thận: Hai thận kích thước bình thường, không có sỏi',
                'Tụy: Kích thước và cấu trúc bình thường',
                'Lách: Kích thước bình thường',
            ],
            // X-quang
            5 => [
                'Phổi: Không phát hiện tổn thương',
                'Tim: Kích thước bình thường',
                'Xương: Không phát hiện gãy xương hoặc tổn thương',
                'Cấu trúc xương khớp bình thường',
                'Không có dấu hiệu bất thường',
            ],
            // Mặc định cho các loại xét nghiệm khác
            'default' => [
                'Kết quả trong giới hạn bình thường',
                'Không phát hiện bất thường',
                'Cần theo dõi thêm',
                'Đề nghị tái khám sau 1 tháng',
                'Đề nghị thực hiện thêm xét nghiệm chuyên sâu',
            ],
        ];

        $laboratoryResults = [];

        foreach ($laboratories as $laboratory) {
            // Lấy kết quả mẫu theo loại xét nghiệm
            $results = isset($resultsByType[$laboratory->LaboratoryTypeID])
                ? $resultsByType[$laboratory->LaboratoryTypeID]
                : $resultsByType['default'];

            // Chọn ngẫu nhiên 1-3 kết quả
            $resultCount = rand(1, 3);
            $selectedResults = array_rand(array_flip($results), $resultCount);

            if (!is_array($selectedResults)) {
                $selectedResults = [$selectedResults];
            }

            foreach ($selectedResults as $result) {
                $laboratoryResults[] = [
                    'LaboratoryID' => $laboratory->LaboratoryID,
                    'Result' => $result,
                    'created_at' => $laboratory->updated_at,
                    'updated_at' => $laboratory->updated_at,
                ];
            }
        }

        DB::table('laboratory_results')->insert($laboratoryResults);
    }
}