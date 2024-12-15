<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PrescriptionController extends Controller
{
    private $sampleMedicines = [
        [
            'MedicineID' => 1,
            'Name' => 'Paracetamol',
            'Type' => 'Tablet',
            'Stock' => 100,
            'Price' => 5000
        ],
        [
            'MedicineID' => 2,
            'Name' => 'Amoxicillin',
            'Type' => 'Capsule',
            'Stock' => 5,  // Low stock
            'Price' => 15000
        ],
        [
            'MedicineID' => 3,
            'Name' => 'Omeprazole',
            'Type' => 'Tablet',
            'Stock' => 0,  // Out of stock
            'Price' => 20000
        ]
    ];

    private $samplePrescriptions = [
        [
            'PrescriptionID' => 1,
            'PatientID' => 1,
            'PatientName' => 'John Doe',
            'DoctorID' => 1,
            'Date' => '2024-03-20',
            'Status' => 'Pending',
            'Items' => [
                [
                    'MedicineID' => 1,
                    'Name' => 'Paracetamol',
                    'Dosage' => '500mg',
                    'Frequency' => '3 times daily',
                    'Duration' => '5 days',
                    'Quantity' => 15
                ]
            ],
            'Notes' => 'Take after meals'
        ]
    ];

    public function index()
    {
        $doctorId = session('doctor_id', 1);
        
        $prescriptions = collect($this->samplePrescriptions)
            ->where('DoctorID', $doctorId)
            ->sortByDesc('Date');

        $medicines = collect($this->sampleMedicines);
        $lowStockMedicines = $medicines->where('Stock', '<=', 5);

        return view('doctor.prescriptions', compact('prescriptions', 'medicines', 'lowStockMedicines'));
    }

    public function create()
    {
        $medicines = collect($this->sampleMedicines)
            ->where('Stock', '>', 0);  // Only show available medicines
        
        return view('doctor.prescription-create', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer',
            'medicines' => 'required|array',
            'medicines.*.id' => 'required|integer',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.frequency' => 'required|string',
            'medicines.*.duration' => 'required|string',
            'medicines.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // In real application, save to database and check stock
        return response()->json(['message' => 'Prescription created successfully']);
    }

    public function show($id)
    {
        $prescription = collect($this->samplePrescriptions)
            ->firstWhere('PrescriptionID', $id);

        if (!$prescription) {
            return redirect()->back()->with('error', 'Prescription not found');
        }

        return view('doctor.prescription-details', compact('prescription'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Cancelled',
            'notes' => 'nullable|string'
        ]);

        // In real application, update database
        return response()->json(['message' => 'Prescription updated successfully']);
    }

    public function reportLowStock(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|integer',
            'notes' => 'nullable|string'
        ]);

        // In real application, send notification to admin
        return response()->json(['message' => 'Low stock report sent successfully']);
    }
} 