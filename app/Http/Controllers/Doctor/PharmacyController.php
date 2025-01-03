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

class PharmacyController extends Controller
{
    // Display the Pharmacy page
    public function index()
    {
        $doctor = Doctor::where('UserID', Auth::id())->first();

        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor profile not found!');
        }

        $patients = User::where('RoleID', 'patient')->get();
        $medicines = Medicine::with('medicineStock')->get();
        $prescriptions = Prescription::with(['user', 'prescriptionDetail.medicine'])
            ->where('DoctorID', $doctor->DoctorID)
            ->get();

        $lowStockMedicines = Medicine::with('stock')
            ->whereHas('medicineStock', function ($query) {
                $query->where('Quantity', '<', 10);
            })
            ->get();

        return view('doctor.pharmacy', compact('patients', 'medicines', 'prescriptions', 'lowStockMedicines'));
    }


    // Store a new Prescription
    public function store(Request $request)
    {
        try {
            $request->validate([
                'patient_id' => 'required|exists:users,UserID',
                'medicines' => 'required|array',
                'medicines.*.id' => 'required|exists:medicines,MedicineID',
                'medicines.*.quantity' => 'required|integer|min:1',
                'medicines.*.dosage' => 'required|string',
                'medicines.*.frequency' => 'required|string',
                'medicines.*.duration' => 'required|string',
            ]);

            // Lấy DoctorID từ bảng doctors thông qua UserID
            $doctor = Doctor::where('UserID', Auth::id())->first();

            if (!$doctor) {
                return response()->json(['success' => false, 'message' => 'Doctor profile not found'], 404);
            }

            // Tạo prescription
            $prescription = Prescription::create([
                'PrescriptionDate' => now(),
                'UserID' => $request->patient_id, // UserID thay cho PatientID
                'DoctorID' => $doctor->DoctorID,
                'TotalPrice' => 0, // Giá sẽ được tính từ các thuốc
            ]);

            $totalPrice = 0;

            foreach ($request->medicines as $medicine) {
                $medicineDetails = Medicine::findOrFail($medicine['id']);
                $price = $medicineDetails->UnitPrice * $medicine['quantity'];
                $totalPrice += $price;

                PrescriptionDetail::create([
                    'PrescriptionID' => $prescription->PrescriptionID,
                    'MedicineID' => $medicine['id'],
                    'Quantity' => $medicine['quantity'],
                    'Price' => $price,
                    'Dosage' => $medicine['dosage'],
                    'Frequency' => $medicine['frequency'],
                    'Duration' => $medicine['duration'],
                ]);
            }

            $prescription->update(['TotalPrice' => $totalPrice]);

            return response()->json(['success' => true, 'message' => 'Prescription created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creating prescription', 'error' => $e->getMessage()], 500);
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
            'Notes' => $prescription->Notes,
            'Status' => $prescription->Status,
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

    // Cancel a Prescription
    public function cancel($id)
    {
        $prescription = Prescription::findOrFail($id);

        if ($prescription->Status !== 'Pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending prescriptions can be canceled.',
            ]);
        }

        // Revert stock for each medicine in the prescription
        foreach ($prescription->prescriptionDetail as $detail) {
            $medicineStock = MedicineStock::where('MedicineID', $detail->MedicineID)->first();
            $medicineStock->increment('Quantity', $detail->Quantity);
        }

        $prescription->update(['Status' => 'Cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Prescription has been canceled.',
        ]);
    }

    // Update a Prescription (e.g., mark as Completed)
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Completed,Cancelled',
        ]);

        $prescription = Prescription::findOrFail($id);

        if ($prescription->Status !== 'Pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending prescriptions can be updated.',
            ]);
        }

        $prescription->update(['Status' => $request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => 'Prescription status updated successfully!',
        ]);
    }
}
