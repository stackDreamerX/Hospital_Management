<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PharmacyController extends Controller
{
    // Move all the prescription-related methods and data here
    // Keep the same functionality but update the view path
    
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

    // ... keep other methods from PrescriptionController
} 