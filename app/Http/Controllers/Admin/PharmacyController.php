<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    private $sampleMedicines = [
        [
            'MedicineID' => 1,
            'MedicineName' => 'Paracetamol',
            'ExpiryDate' => '2024-12-31',
            'ManufacturingDate' => '2023-01-01',
            'UnitPrice' => 5000,
            'stock' => ['Quantity' => 100]
        ],
        [
            'MedicineID' => 2,
            'MedicineName' => 'Amoxicillin',
            'ExpiryDate' => '2024-06-30',
            'ManufacturingDate' => '2023-01-01',
            'UnitPrice' => 15000,
            'stock' => ['Quantity' => 15]
        ],
        [
            'MedicineID' => 3,
            'MedicineName' => 'Omeprazole',
            'ExpiryDate' => '2025-12-31',
            'ManufacturingDate' => '2023-06-01',
            'UnitPrice' => 20000,
            'stock' => ['Quantity' => 0]
        ]
    ];

    private $sampleProviders = [
        [
            'ProviderID' => 1,
            'ProviderName' => 'PharmaCorp Ltd.'
        ],
        [
            'ProviderID' => 2,
            'ProviderName' => 'MediSupply Inc.'
        ],
        [
            'ProviderID' => 3,
            'ProviderName' => 'Global Pharmaceuticals'
        ]
    ];

    public function pharmacy()
    {
        $medicines = collect($this->sampleMedicines);
        $providers = collect($this->sampleProviders);
        $prescriptions = collect([
            [
                'PrescriptionID' => 1,
                'PrescriptionDate' => '2024-03-20',
                'PatientName' => 'John Doe',
                'DoctorName' => 'Dr. Sarah Wilson',
                'TotalItems' => 3,
                'TotalPrice' => 75000
            ],
            [
                'PrescriptionID' => 2,
                'PrescriptionDate' => '2024-03-21',
                'PatientName' => 'Jane Smith',
                'DoctorName' => 'Dr. Michael Brown',
                'TotalItems' => 2,
                'TotalPrice' => 45000
            ]
        ]);
        
        $todayPrescriptions = 5;
        $lowStockCount = $medicines->where('stock.Quantity', '<', 20)->count();

        return view('admin.pharmacy', compact(
            'medicines',
            'providers',
            'prescriptions',
            'todayPrescriptions',
            'lowStockCount'
        ));
    }
}
