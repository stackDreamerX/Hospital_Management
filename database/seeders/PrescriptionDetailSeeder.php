<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrescriptionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách các đơn thuốc
        $prescriptions = DB::table('prescriptions')->get();

        // Lấy danh sách các thuốc
        $medicines = DB::table('medicines')->get();

        // Danh sách các liều lượng mẫu
        $dosages = [
            '1 viên',
            '2 viên',
            '5ml',
            '10ml',
            '1/2 viên',
            '1 gói',
            '1 ống',
            '2 ống',
            '1 thìa cà phê',
            '1 thìa canh',
        ];

        // Danh sách các tần suất sử dụng mẫu
        $frequencies = [
            'Ngày 2 lần sau bữa ăn',
            'Ngày 3 lần sau bữa ăn',
            'Ngày 1 lần trước khi ngủ',
            'Ngày 4 lần (mỗi 6 giờ)',
            'Mỗi 8 giờ',
            'Mỗi 12 giờ',
            'Khi cần (không quá 4 lần/ngày)',
            'Sáng 1 lần, tối 1 lần',
            'Sau bữa ăn sáng',
            'Trước bữa ăn 30 phút',
        ];

        // Danh sách các thời gian điều trị mẫu
        $durations = [
            '3 ngày',
            '5 ngày',
            '7 ngày',
            '10 ngày',
            '14 ngày',
            '1 tháng',
            '2 tuần',
            'Đến khi hết thuốc',
            'Khi có triệu chứng',
            'Liên tục',
        ];

        $prescriptionDetails = [];
        $prescriptionTotals = [];

        // Tạo chi tiết đơn thuốc cho mỗi đơn
        foreach ($prescriptions as $prescription) {
            // Mỗi đơn thuốc có 2-5 loại thuốc
            $medicineCount = rand(2, 5);
            $totalPrice = 0;

            // Chọn ngẫu nhiên các loại thuốc không trùng lặp
            $selectedMedicines = $medicines->random($medicineCount);

            foreach ($selectedMedicines as $medicine) {
                $quantity = rand(1, 3) * 10; // Số lượng thuốc (10, 20, 30)
                $price = $medicine->UnitPrice * $quantity;
                $totalPrice += $price;

                $prescriptionDetails[] = [
                    'PrescriptionID' => $prescription->PrescriptionID,
                    'MedicineID' => $medicine->MedicineID,
                    'Quantity' => $quantity,
                    'Price' => $price,
                    'Dosage' => $dosages[array_rand($dosages)],
                    'Frequency' => $frequencies[array_rand($frequencies)],
                    'Duration' => $durations[array_rand($durations)],
                    'created_at' => $prescription->created_at,
                    'updated_at' => $prescription->updated_at,
                ];
            }

            // Lưu tổng giá trị đơn thuốc để cập nhật sau
            $prescriptionTotals[$prescription->PrescriptionID] = $totalPrice;
        }

        // Thêm chi tiết đơn thuốc vào database
        DB::table('prescription_details')->insert($prescriptionDetails);

        // Cập nhật tổng giá trị cho mỗi đơn thuốc
        foreach ($prescriptionTotals as $prescriptionId => $totalPrice) {
            DB::table('prescriptions')
                ->where('PrescriptionID', $prescriptionId)
                ->update(['TotalPrice' => $totalPrice]);
        }
    }
}