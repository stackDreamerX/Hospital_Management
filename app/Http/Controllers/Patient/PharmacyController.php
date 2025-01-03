<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Prescription;
use App\Models\PrescriptionDetail;

class PharmacyController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Lấy UserID từ Auth
        $prescriptions = Prescription::with(['doctor.user', 'prescriptionDetail.medicine'])
            ->where('UserID', $userId)
            ->orderBy('PrescriptionDate', 'desc')
            ->get();

        $formattedPrescriptions = $prescriptions->map(function ($prescription) {
            return [
                'PrescriptionID' => $prescription->PrescriptionID,
                'Date' => $prescription->PrescriptionDate,
                'DoctorName' => $prescription->doctor->user->FullName ?? 'N/A',
                'Items' => $prescription->prescriptionDetail->map(function ($detail) {
                    return [
                        'Name' => $detail->medicine->MedicineName,
                        'Dosage' => $detail->Dosage,
                        'Frequency' => $detail->Frequency,
                        'Duration' => $detail->Duration,
                        'Quantity' => $detail->Quantity,
                        'Price' => $detail->Price,
                    ];
                }),
                'TotalAmount' => $prescription->TotalPrice,
                'Status' => 'Completed',
                'PaymentStatus' => 'Paid',
                'Notes' => $prescription->Notes ?? null,
            ];
        });

        return view('patient.pharmacy', [
            'prescriptions' => $formattedPrescriptions,
            'pendingCount' => $formattedPrescriptions->where('Status', 'Pending')->count(),
            'completedCount' => $formattedPrescriptions->where('Status', 'Completed')->count(),
            'totalSpent' => $formattedPrescriptions->sum('TotalAmount'),
        ]);
    }

    public function show($id)
    {
        $prescription = Prescription::with(['doctor.user', 'prescriptionDetail.medicine'])
            ->where('PrescriptionID', $id)
            ->firstOrFail();

        $formattedPrescription = [
            'PrescriptionID' => $prescription->PrescriptionID,
            'Date' => $prescription->PrescriptionDate,
            'DoctorName' => $prescription->doctor->user->FullName ?? 'N/A',
            'Items' => $prescription->prescriptionDetail->map(function ($detail) {
                return [
                    'Name' => $detail->medicine->MedicineName,
                    'Dosage' => $detail->Dosage,
                    'Frequency' => $detail->Frequency,
                    'Duration' => $detail->Duration,
                    'Quantity' => $detail->Quantity,
                    'Price' => $detail->Price,
                ];
            }),
            'TotalAmount' => $prescription->TotalPrice,
            'Notes' => $prescription->Notes ?? 'N/A',
            'Status' => 'Completed',
            'PaymentStatus' => 'Paid',
        ];

        return response()->json($formattedPrescription);
    }
}
