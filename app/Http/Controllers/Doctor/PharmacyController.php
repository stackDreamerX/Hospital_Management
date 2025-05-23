<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PrescriptionDetail;
use App\Models\Medicine;
use App\Models\MedicineStock;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;

class PharmacyController extends Controller
{
    // Display the Pharmacy page
    public function index(Request $request)
    {
        $doctor = Doctor::where('UserID', Auth::id())->first();

        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor profile not found!');
        }

        $patients = User::where('RoleID', 'patient')->get();
        $medicines = Medicine::with('medicineStock')->get();
        $prescriptions = Prescription::with(['user', 'prescriptionDetail.medicine'])
            ->where('DoctorID', $doctor->DoctorID)
            ->orderBy('PrescriptionDate', 'desc')
            ->get();

        $lowStockMedicines = Medicine::with('stock')
            ->whereHas('medicineStock', function ($query) {
                $query->where('Quantity', '<', 10);
            })
            ->get();

        // Get the patient_id from the query string if available
        $selectedPatientId = $request->query('patient_id');
        $selectedPatient = null;

        if ($selectedPatientId) {
            $selectedPatient = User::find($selectedPatientId);
        }

        return view('doctor.pharmacy', compact('patients', 'medicines', 'prescriptions', 'lowStockMedicines', 'selectedPatient', 'selectedPatientId'));
    }


    // Store a new Prescription
    public function store(Request $request)
    {
        try {
            // Log dữ liệu nhận được
            Log::info('Prescription create - Request data:', $request->all());

            $request->validate([
                'patient_id' => 'required|exists:users,UserID',
                'medicines' => 'required|array',
                'medicines.*.id' => 'required|exists:medicines,MedicineID',
                'medicines.*.quantity' => 'required|integer|min:1',
                'medicines.*.dosage' => 'required|string',
                'medicines.*.frequency' => 'required|string',
                'medicines.*.duration' => 'required|string',
                'diagnosis' => 'nullable|string',
                'test_results' => 'nullable|string',
                'blood_pressure' => 'nullable|string',
                'heart_rate' => 'nullable|integer',
                'temperature' => 'nullable|string',
                'spo2' => 'nullable|integer',
                'instructions' => 'nullable|string',
            ]);

            // Lấy DoctorID từ bảng doctors thông qua UserID
            $doctor = Doctor::where('UserID', Auth::id())->first();
            Log::info('Doctor information:', ['doctor' => $doctor ? $doctor->toArray() : null]);

            if (!$doctor) {
                Log::error('Doctor profile not found for user ID: ' . Auth::id());
                return response()->json(['success' => false, 'message' => 'Doctor profile not found'], 404);
            }

            // Log các trường sẽ tạo
            $prescriptionData = [
                'PrescriptionDate' => now(),
                'UserID' => $request->patient_id,
                'DoctorID' => $doctor->DoctorID,
                'TotalPrice' => 0,
                'Diagnosis' => $request->diagnosis,
                'TestResults' => $request->test_results,
                'BloodPressure' => $request->blood_pressure,
                'HeartRate' => $request->heart_rate,
                'Temperature' => $request->temperature,
                'SpO2' => $request->spo2,
                'Instructions' => $request->instructions,
            ];
            Log::info('Creating prescription with data:', $prescriptionData);

            // Tạo prescription
            $prescription = Prescription::create($prescriptionData);
            Log::info('Prescription created:', ['prescription_id' => $prescription->PrescriptionID]);

            $totalPrice = 0;

            // Log thông tin thuốc
            Log::info('Processing medicines:', ['medicines' => $request->medicines]);

            foreach ($request->medicines as $medicine) {
                try {
                    $medicineDetails = Medicine::findOrFail($medicine['id']);
                    $price = $medicineDetails->UnitPrice * $medicine['quantity'];
                    $totalPrice += $price;

                    $prescDetail = [
                        'PrescriptionID' => $prescription->PrescriptionID,
                        'MedicineID' => $medicine['id'],
                        'Quantity' => $medicine['quantity'],
                        'Price' => $price,
                        'Dosage' => $medicine['dosage'],
                        'Frequency' => $medicine['frequency'],
                        'Duration' => $medicine['duration'],
                    ];
                    Log::info('Creating prescription detail:', $prescDetail);

                    PrescriptionDetail::create($prescDetail);
                } catch (\Exception $medicineEx) {
                    Log::error('Error processing medicine:', [
                        'medicine_id' => $medicine['id'] ?? 'unknown',
                        'error' => $medicineEx->getMessage(),
                        'trace' => $medicineEx->getTraceAsString()
                    ]);
                    throw $medicineEx;
                }
            }

            $prescription->update(['TotalPrice' => $totalPrice]);
            Log::info('Prescription updated with total price:', ['price' => $totalPrice]);

            return response()->json([
                'success' => true,
                'message' => 'Prescription created successfully',
                'id' => $prescription->PrescriptionID
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating prescription:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Error creating prescription: ' . $e->getMessage()], 500);
        }
    }


    // Show a specific Prescription
    public function show($id)
    {
        $prescription = Prescription::with(['user', 'prescriptionDetail.medicine'])->findOrFail($id);

        return response()->json([
            'PrescriptionID' => $prescription->PrescriptionID,
            'PatientName' => $prescription->user->FullName,
            'Date' => $prescription->PrescriptionDate,
            'Diagnosis' => $prescription->Diagnosis,
            'TestResults' => $prescription->TestResults,
            'BloodPressure' => $prescription->BloodPressure,
            'HeartRate' => $prescription->HeartRate,
            'Temperature' => $prescription->Temperature,
            'SpO2' => $prescription->SpO2,
            'Instructions' => $prescription->Instructions,
            'Notes' => $prescription->Notes,
            'Medicines' => $prescription->prescriptionDetail->map(function ($detail) {
                return [
                    'Name' => $detail->medicine->MedicineName,
                    'Dosage' => $detail->Dosage,
                    'Frequency' => $detail->Frequency,
                    'Duration' => $detail->Duration,
                    'Quantity' => $detail->Quantity,
                    'Price' => $detail->Price,
                ];
            }),
        ]);
    }

    // Tạo và tải xuống PDF cho đơn thuốc
    public function downloadPdf($id)
    {
        $prescription = Prescription::with(['user', 'prescriptionDetail.medicine', 'doctor.user'])->findOrFail($id);
        $doctor = $prescription->doctor;

        // Cấu hình dompdf
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        // Khởi tạo Dompdf
        $dompdf = new Dompdf($options);

        // Render view thành HTML
        $html = view('doctor.prescription_pdf_full', [
            'prescription' => $prescription,
            'doctor' => $doctor,
        ])->render();

        // Load HTML vào Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Tạo file PDF và tải xuống
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="don_thuoc_'.$id.'.pdf"',
        ]);
    }

    // Delete a Prescription
    public function cancel($id)
    {
        try {
            Log::info('Deleting prescription with ID: ' . $id);

            $prescription = Prescription::with('prescriptionDetail')->findOrFail($id);

            // Xóa chi tiết đơn thuốc
            foreach ($prescription->prescriptionDetail as $detail) {
                $detail->delete();
            }

            // Xóa đơn thuốc
            $prescription->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đơn thuốc đã được xóa thành công.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting prescription:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi xóa đơn thuốc: ' . $e->getMessage()
            ], 500);
        }
    }
}
