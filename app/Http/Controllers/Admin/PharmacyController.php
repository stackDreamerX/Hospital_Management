<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\MedicineStock;
use App\Models\Medicine;

class PharmacyController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with(['user', 'doctor.user'])->get(); // Lấy tất cả đơn thuốc
        $medicines = Medicine::with('medicineStock')->get(); // Lấy thông tin thuốc
        $lowStockCount = MedicineStock::where('Quantity', '<', 10)->count(); // Đếm thuốc sắp hết
        $todayPrescriptions = Prescription::whereDate('created_at', today())->count(); // Đếm đơn thuốc hôm nay
        $totalPrescriptions = $prescriptions->count(); // Tính tổng số đơn thuốc

        return view('admin.pharmacy', compact(
            'prescriptions',
            'medicines',
            'lowStockCount',
            'todayPrescriptions',
            'totalPrescriptions'
        ));
    }


    public function show($id)
    {
        $prescription = Prescription::with(['user', 'doctor.user', 'prescriptionDetail.medicine'])
            ->findOrFail($id);

        return response()->json([
            'PrescriptionID' => $prescription->PrescriptionID,
            'PrescriptionDate' => $prescription->PrescriptionDate,
            'PatientName' => $prescription->user->FullName,
            'DoctorName' => $prescription->doctor->user->FullName,
            'TotalPrice' => $prescription->TotalPrice,
            'Status' => $prescription->Status,
            'Items' => $prescription->prescriptionDetail->map(function ($detail) {
                return [
                    'MedicineName' => $detail->medicine->MedicineName,
                    'Dosage' => $detail->Dosage,
                    'Quantity' => $detail->Quantity,
                    'Price' => $detail->Price,
                ];
            }),
        ]);
    }

}
