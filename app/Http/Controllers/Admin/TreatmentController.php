<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    private $sampleTreatmentTypes = [
        ['TreatmentTypeID' => 1, 'TreatmentTypeName' => 'Dental Cleaning'],
        ['TreatmentTypeID' => 2, 'TreatmentTypeName' => 'Root Canal'],
        ['TreatmentTypeID' => 3, 'TreatmentTypeName' => 'Physical Therapy'],
        ['TreatmentTypeID' => 4, 'TreatmentTypeName' => 'Surgery'],
    ];

    private $sampleTreatments = [
        [
            'TreatmentID' => 1,
            'TreatmentTypeID' => 1,
            'TreatmentTypeName' => 'Dental Cleaning',
            'TreatmentDate' => '2024-03-20',
            'PatientID' => 1,
            'PatientName' => 'John Doe',
            'DoctorID' => 1,
            'DoctorName' => 'Dr. Sarah Wilson',
            'TotalPrice' => 150000,
            'Result' => 'Treatment completed successfully'
        ],
        [
            'TreatmentID' => 2,
            'TreatmentTypeID' => 2,
            'TreatmentTypeName' => 'Root Canal',
            'TreatmentDate' => '2024-03-21',
            'PatientID' => 2,
            'PatientName' => 'Jane Smith',
            'DoctorID' => 2,
            'DoctorName' => 'Dr. Michael Brown',
            'TotalPrice' => 500000,
            'Result' => 'Follow-up required in 2 weeks'
        ]
    ];

    public function treatment()
    {
        $treatmentTypes = collect($this->sampleTreatmentTypes);
        $treatments = collect($this->sampleTreatments);
        $patients = collect([
            ['PatientID' => 1, 'FullName' => 'John Doe'],
            ['PatientID' => 2, 'FullName' => 'Jane Smith']
        ]);
        $doctors = collect([
            ['DoctorID' => 1, 'FullName' => 'Dr. Sarah Wilson'],
            ['DoctorID' => 2, 'FullName' => 'Dr. Michael Brown']
        ]);

        return view('admin.treatment', compact(
            'treatmentTypes',
            'treatments',
            'patients',
            'doctors'
        ));
    }
}
