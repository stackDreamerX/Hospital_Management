<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PharmacyController extends Controller
{
    private $samplePrescriptions = [
        [
            'PrescriptionID' => 1,
            'PatientID' => 1,
            'DoctorName' => 'Dr. Sarah Wilson',
            'Date' => '2024-03-20',
            'Status' => 'Pending',
            'PaymentStatus' => 'Pending',
            'TotalAmount' => 2500,
            'Notes' => 'Take after meals',
            'Items' => [
                [
                    'MedicineID' => 1,
                    'Name' => 'Paracetamol',
                    'Dosage' => '500mg',
                    'Frequency' => '3 times daily',
                    'Duration' => '5 days',
                    'Quantity' => 15,
                    'Price' => 500
                ],
                [
                    'MedicineID' => 2,
                    'Name' => 'Vitamin C',
                    'Dosage' => '1000mg',
                    'Frequency' => 'Once daily',
                    'Duration' => '30 days',
                    'Quantity' => 30,
                    'Price' => 2000
                ]
            ]
        ],
        [
            'PrescriptionID' => 2,
            'PatientID' => 1,
            'DoctorName' => 'Dr. Michael Brown',
            'Date' => '2024-03-15',
            'Status' => 'Completed',
            'PaymentStatus' => 'Paid',
            'TotalAmount' => 1500,
            'Notes' => 'Complete course as prescribed',
            'Items' => [
                [
                    'MedicineID' => 3,
                    'Name' => 'Amoxicillin',
                    'Dosage' => '250mg',
                    'Frequency' => '2 times daily',
                    'Duration' => '7 days',
                    'Quantity' => 14,
                    'Price' => 1500
                ]
            ]
        ]
    ];

    public function index()
    {
        $patientId = session('patient_id', 1);
        
        $prescriptions = collect($this->samplePrescriptions)
            ->where('PatientID', $patientId)
            ->sortByDesc('Date');

        $pendingCount = $prescriptions->where('Status', 'Pending')->count();
        $completedCount = $prescriptions->where('Status', 'Completed')->count();
        $totalSpent = $prescriptions->where('PaymentStatus', 'Paid')->sum('TotalAmount');

        return view('patient.pharmacy', compact(
            'prescriptions',
            'pendingCount',
            'completedCount',
            'totalSpent'
        ));
    }

    public function show($id)
    {
        $prescription = collect($this->samplePrescriptions)
            ->firstWhere('PrescriptionID', $id);

        if (!$prescription) {
            return redirect()->back()->with('error', 'Prescription not found');
        }

        return view('patient.prescription-details', compact('prescription'));
    }

    public function downloadPrescription($id)
    {
        // In real application, generate and download PDF
        return response()->json(['message' => 'Prescription downloaded successfully']);
    }
} 