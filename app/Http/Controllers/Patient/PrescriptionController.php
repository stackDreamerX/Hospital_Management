<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    /**
     * Display the specified prescription.
     */
    public function show($id)
    {
        $prescription = Prescription::with(['doctor.user', 'prescriptionDetails.medicine'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('patient.pharmacy.show', compact('prescription'));
    }
} 