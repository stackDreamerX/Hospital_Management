<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra và tạo patient cụ thể
        $patientUser = DB::table('users')->where('username', 'huynhtrung1904')->first();

        if (!$patientUser) {
            $patientId = DB::table('users')->insertGetId([
                'RoleID' => 'patient',
                'username' => 'huynhtrung1904',
                'FullName' => 'Huỳnh Trung',
                'Email' => 'huynhtrung1904@gmail.com',
                'password' => bcrypt('123456'),
                'PhoneNumber' => '0912345678',
                'DateOfBirth' => '1990-04-19',
                'Gender' => 'Male',
                'Address' => '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $patientId = $patientUser->UserID;
        }

        // Kiểm tra và tạo doctor cụ thể
        $doctorUser = DB::table('users')->where('username', 'doctor')->first();

        if (!$doctorUser) {
            $doctorUserId = DB::table('users')->insertGetId([
                'RoleID' => 'doctor',
                'username' => 'doctor',
                'FullName' => 'Nguyễn Văn Khoa',
                'Email' => 'doctor@hospital.com',
                'password' => bcrypt('123456'),
                'PhoneNumber' => '0987654321',
                'DateOfBirth' => '1985-06-15',
                'Gender' => 'Male',
                'Address' => '456 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Thêm thông tin bác sĩ
            $doctorId = DB::table('doctors')->insertGetId([
                'UserID' => $doctorUserId,
                'Speciality' => 'Nội khoa',
                'Title' => 'Bác sĩ chuyên khoa II',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $doctorUserId = $doctorUser->UserID;
            $doctor = DB::table('doctors')->where('UserID', $doctorUserId)->first();
            $doctorId = $doctor ? $doctor->DoctorID : null;

            // Nếu chưa có thông tin bác sĩ, thêm mới
            if (!$doctorId) {
                $doctorId = DB::table('doctors')->insertGetId([
                    'UserID' => $doctorUserId,
                    'Speciality' => 'Nội khoa',
                    'Title' => 'Bác sĩ chuyên khoa II',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Thêm lịch hẹn giữa patient và doctor
        $this->createAppointments($patientId, $doctorId);

        // Thêm xét nghiệm cho patient
        $this->createLaboratories($patientId, $doctorId);

        // Thêm đơn thuốc cho patient
        $this->createPrescriptions($patientId, $doctorId);

        // Thêm điều trị cho patient
        $this->createTreatments($patientId, $doctorId);

        // Thêm hóa đơn cho patient
        $this->createInvoices($patientId);
    }

    /**
     * Tạo lịch hẹn cho demo
     */
    private function createAppointments($patientId, $doctorId)
    {
        // Lịch hẹn đang chờ
        DB::table('appointments')->insert([
            'AppointmentDate' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'AppointmentTime' => '09:00:00',
            'UserID' => $patientId,
            'DoctorID' => $doctorId,
            'Status' => 'pending',
            'Reason' => 'Khám sức khỏe định kỳ',
            'Symptoms' => 'Mệt mỏi, đau đầu nhẹ',
            'Notes' => 'Cần kiểm tra huyết áp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Lịch hẹn đã xác nhận
        DB::table('appointments')->insert([
            'AppointmentDate' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'AppointmentTime' => '14:30:00',
            'UserID' => $patientId,
            'DoctorID' => $doctorId,
            'Status' => 'approved',
            'Reason' => 'Tái khám sau điều trị',
            'Symptoms' => 'Đau họng, ho nhẹ',
            'Notes' => 'Theo dõi sau khi uống thuốc 3 ngày',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Lịch hẹn đã hoàn thành
        DB::table('appointments')->insert([
            'AppointmentDate' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'AppointmentTime' => '10:15:00',
            'UserID' => $patientId,
            'DoctorID' => $doctorId,
            'Status' => 'completed',
            'Reason' => 'Khám bệnh đau dạ dày',
            'Symptoms' => 'Đau bụng, ợ chua, khó tiêu',
            'Notes' => 'Đã điều trị và kê đơn',
            'DoctorNotes' => 'Bệnh nhân có dấu hiệu viêm dạ dày, cần uống thuốc đều đặn và kiêng đồ cay nóng',
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'amount' => 200000,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ]);
    }

    /**
     * Tạo xét nghiệm cho demo
     */
    private function createLaboratories($patientId, $doctorId)
    {
        // Lấy danh sách các loại xét nghiệm
        $laboratoryTypes = DB::table('laboratory_types')
            ->select('LaboratoryTypeID', 'Price')
            ->get();

        if (count($laboratoryTypes) >= 4) {
            $laboratories = [
                [
                    'LaboratoryTypeID' => $laboratoryTypes[0]->LaboratoryTypeID,
                    'LaboratoryDate' => Carbon::now()->subDays(10)->format('Y-m-d'),
                    'LaboratoryTime' => '09:30:00',
                    'UserID' => $patientId,
                    'DoctorID' => $doctorId,
                    'TotalPrice' => $laboratoryTypes[0]->Price,
                    'created_at' => now()->subDays(10),
                    'updated_at' => now()->subDays(10),
                ],
                [
                    'LaboratoryTypeID' => $laboratoryTypes[1]->LaboratoryTypeID,
                    'LaboratoryDate' => Carbon::now()->subDays(10)->format('Y-m-d'),
                    'LaboratoryTime' => '10:00:00',
                    'UserID' => $patientId,
                    'DoctorID' => $doctorId,
                    'TotalPrice' => $laboratoryTypes[1]->Price,
                    'created_at' => now()->subDays(10),
                    'updated_at' => now()->subDays(10),
                ],
                [
                    'LaboratoryTypeID' => $laboratoryTypes[3]->LaboratoryTypeID,
                    'LaboratoryDate' => Carbon::now()->subDays(5)->format('Y-m-d'),
                    'LaboratoryTime' => '14:15:00',
                    'UserID' => $patientId,
                    'DoctorID' => $doctorId,
                    'TotalPrice' => $laboratoryTypes[3]->Price,
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(5),
                ],
            ];

            // Thêm xét nghiệm
            foreach ($laboratories as $lab) {
                $labId = DB::table('laboratories')->insertGetId($lab);

                // Thêm kết quả xét nghiệm
                DB::table('laboratory_results')->insert([
                    'LaboratoryID' => $labId,
                    'Result' => 'Kết quả xét nghiệm trong giới hạn bình thường',
                    'created_at' => Carbon::parse($lab['LaboratoryDate'])->addDays(1),
                    'updated_at' => Carbon::parse($lab['LaboratoryDate'])->addDays(1),
                ]);
            }
        }
    }

    /**
     * Tạo đơn thuốc cho demo
     */
    private function createPrescriptions($patientId, $doctorId)
    {
        // Lấy danh sách thuốc
        $medicines = DB::table('medicines')
            ->select('MedicineID', 'UnitPrice')
            ->get();

        if (count($medicines) > 0) {
            // Tạo đơn thuốc
            $prescriptionId = DB::table('prescriptions')->insertGetId([
                'PrescriptionDate' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'UserID' => $patientId,
                'DoctorID' => $doctorId,
                'TotalPrice' => 450000,
                'Diagnosis' => 'Viêm dạ dày cấp tính',
                'TestResults' => 'Kết quả nội soi: Viêm niêm mạc dạ dày',
                'BloodPressure' => '120/80',
                'HeartRate' => 75,
                'Temperature' => '36.8',
                'SpO2' => 98,
                'Instructions' => 'Uống thuốc đều đặn, tránh thức ăn cay nóng, không uống rượu bia',
                'Status' => 'Completed',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]);

            // Thêm chi tiết đơn thuốc
            if (count($medicines) >= 3) {
                $prescriptionDetails = [
                    [
                        'PrescriptionID' => $prescriptionId,
                        'MedicineID' => $medicines[0]->MedicineID,
                        'Quantity' => 2,
                        'Price' => $medicines[0]->UnitPrice,
                        'Dosage' => '1 viên',
                        'Frequency' => 'Ngày 2 lần sau ăn',
                        'created_at' => now()->subDays(10),
                        'updated_at' => now()->subDays(10),
                    ],
                    [
                        'PrescriptionID' => $prescriptionId,
                        'MedicineID' => $medicines[1]->MedicineID,
                        'Quantity' => 1,
                        'Price' => $medicines[1]->UnitPrice,
                        'Dosage' => '2 viên',
                        'Frequency' => 'Ngày 3 lần sau ăn',
                        'created_at' => now()->subDays(10),
                        'updated_at' => now()->subDays(10),
                    ],
                    [
                        'PrescriptionID' => $prescriptionId,
                        'MedicineID' => $medicines[2]->MedicineID,
                        'Quantity' => 1,
                        'Price' => $medicines[2]->UnitPrice,
                        'Dosage' => '1 gói',
                        'Frequency' => 'Ngày 2 lần trước ăn',
                        'created_at' => now()->subDays(10),
                        'updated_at' => now()->subDays(10),
                    ],
                ];

                DB::table('prescription_details')->insert($prescriptionDetails);
            }
        }
    }

    /**
     * Tạo điều trị cho demo
     */
    private function createTreatments($patientId, $doctorId)
    {
        // Lấy danh sách loại điều trị
        $treatmentTypes = DB::table('treatment_types')
            ->select('TreatmentTypeID')
            ->get();

        if (count($treatmentTypes) > 0) {
            $treatments = [
                [
                    'TreatmentTypeID' => $treatmentTypes[0]->TreatmentTypeID,
                    'TreatmentDate' => Carbon::now()->subDays(8)->format('Y-m-d'),
                    'UserID' => $patientId,
                    'DoctorID' => $doctorId,
                    'TotalPrice' => 350000,
                    'Duration' => 30,
                    'Notes' => 'Điều trị theo đúng quy trình',
                    'Status' => 'completed',
                    'created_at' => now()->subDays(8),
                    'updated_at' => now()->subDays(8),
                ],
            ];

            DB::table('treatments')->insert($treatments);
        }
    }

    /**
     * Tạo hóa đơn cho demo
     */
    private function createInvoices($patientId)
    {
        $invoiceId = DB::table('invoices')->insertGetId([
            'InvoiceDate' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'PatientID' => $patientId,
            'TotalPrice' => 650000,
            'Status' => 'paid',
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ]);

        // Lấy ID của prescription đã tạo
        $prescription = DB::table('prescriptions')
            ->where('UserID', $patientId)
            ->first();

        if ($prescription) {
            DB::table('invoice_prescriptions')->insert([
                'InvoiceID' => $invoiceId,
                'PrescriptionID' => $prescription->PrescriptionID,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]);
        }

        // Lấy ID của treatment đã tạo
        $treatment = DB::table('treatments')
            ->where('UserID', $patientId)
            ->first();

        if ($treatment) {
            DB::table('invoice_treatments')->insert([
                'InvoiceID' => $invoiceId,
                'TreatmentID' => $treatment->TreatmentID,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]);
        }

        // Lấy ID của laboratory đã tạo
        $laboratory = DB::table('laboratories')
            ->where('UserID', $patientId)
            ->first();

        if ($laboratory) {
            DB::table('invoice_laboratories')->insert([
                'InvoiceID' => $invoiceId,
                'LaboratoryID' => $laboratory->LaboratoryID,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]);
        }
    }
}