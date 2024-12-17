<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
// use App\Http\Controllers\Doctor\PrescriptionController;

class PharmacyController extends Controller
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
    
    // public function __construct(PrescriptionController $prescriptionController)
    // {
    //     $this->prescriptionController = $prescriptionController;
    // }
    
    public function index()
    {
        $doctorId = session('doctor_id', 1);
        
        $prescriptions = collect($this->samplePrescriptions)
            ->where('DoctorID', $doctorId)
            ->sortByDesc('Date');

        $medicines = collect($this->sampleMedicines);
        $lowStockMedicines = $medicines->where('Stock', '<=', 5);
        
        $patients = collect($this->getMyPatients($doctorId));

        return view('doctor.pharmacy', compact(
            'prescriptions', 
            'medicines', 
            'lowStockMedicines',
            'patients'
        ));
    }

    private function getMyPatients($doctorId)
    {
        return [
            [
                'PatientID' => 101,
                'FullName' => 'John Smith',
                'Age' => 45,
                'LastVisit' => '2024-03-15'
            ],
            [
                'PatientID' => 102,
                'FullName' => 'Sarah Johnson',
                'Age' => 32,
                'LastVisit' => '2024-03-14'
            ],
            [
                'PatientID' => 103,
                'FullName' => 'Michael Brown',
                'Age' => 58,
                'LastVisit' => '2024-03-13'
            ]
        ];
    }

    // ... keep other methods from PrescriptionController
} 