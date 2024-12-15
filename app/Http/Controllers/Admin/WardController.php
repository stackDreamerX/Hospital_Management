<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WardController extends Controller
{
    private $sampleWardTypes = [
        [
            'WardTypeID' => 1,
            'TypeName' => 'General Ward',
            'Description' => 'For general medical care and observation'
        ],
        [
            'WardTypeID' => 2,
            'TypeName' => 'ICU',
            'Description' => 'Intensive Care Unit for critical patients'
        ],
        [
            'WardTypeID' => 3,
            'TypeName' => 'Pediatric Ward',
            'Description' => 'Specialized care for children'
        ],
        [
            'WardTypeID' => 4,
            'TypeName' => 'Maternity Ward',
            'Description' => 'For pregnancy and childbirth care'
        ],
        [
            'WardTypeID' => 5,
            'TypeName' => 'Surgery Ward',
            'Description' => 'Post-operative care and recovery'
        ]
    ];

    private $sampleWards = [
        [
            'WardID' => 1,
            'WardTypeID' => 1,
            'TypeName' => 'General Ward',
            'WardName' => 'General Ward A',
            'Capacity' => 30,
            'CurrentOccupancy' => 25,
            'DoctorID' => 1,
            'DoctorName' => 'Dr. Sarah Wilson'
        ],
        [
            'WardID' => 2,
            'WardTypeID' => 2,
            'TypeName' => 'ICU',
            'WardName' => 'ICU-1',
            'Capacity' => 10,
            'CurrentOccupancy' => 9,
            'DoctorID' => 2,
            'DoctorName' => 'Dr. Michael Brown'
        ],
        [
            'WardID' => 3,
            'WardTypeID' => 3,
            'TypeName' => 'Pediatric Ward',
            'WardName' => 'Children Ward',
            'Capacity' => 20,
            'CurrentOccupancy' => 12,
            'DoctorID' => 3,
            'DoctorName' => 'Dr. Emily Davis'
        ],
        [
            'WardID' => 4,
            'WardTypeID' => 4,
            'TypeName' => 'Maternity Ward',
            'WardName' => 'Maternity A',
            'Capacity' => 15,
            'CurrentOccupancy' => 8,
            'DoctorID' => 4,
            'DoctorName' => 'Dr. Jessica Taylor'
        ],
        [
            'WardID' => 5,
            'WardTypeID' => 5,
            'TypeName' => 'Surgery Ward',
            'WardName' => 'Post-Op Care',
            'Capacity' => 25,
            'CurrentOccupancy' => 20,
            'DoctorID' => 5,
            'DoctorName' => 'Dr. James Anderson'
        ]
    ];

    private $sampleDoctors = [
        ['DoctorID' => 1, 'FullName' => 'Dr. Sarah Wilson'],
        ['DoctorID' => 2, 'FullName' => 'Dr. Michael Brown'],
        ['DoctorID' => 3, 'FullName' => 'Dr. Emily Davis'],
        ['DoctorID' => 4, 'FullName' => 'Dr. Jessica Taylor'],
        ['DoctorID' => 5, 'FullName' => 'Dr. James Anderson']
    ];

    public function ward()
    {
        $wardTypes = collect($this->sampleWardTypes);
        $wards = collect($this->sampleWards);
        $doctors = collect($this->sampleDoctors);

        return view('admin.ward', compact('wardTypes', 'wards', 'doctors'));
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'ward_name' => 'required|string|max:100',
            'ward_type' => 'required|integer',
            'capacity' => 'required|integer|min:1',
            'doctor_id' => 'required|integer'
        ]);

        // In a real application, you would save to database here
        return response()->json(['message' => 'Ward created successfully']);
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'ward_name' => 'required|string|max:100',
            'ward_type' => 'required|integer',
            'capacity' => 'required|integer|min:1',
            'doctor_id' => 'required|integer'
        ]);

        // In a real application, you would update the database here
        return response()->json(['message' => 'Ward updated successfully']);
    }

    public function destroy($id)
    {
        // In a real application, you would delete from database here
        return response()->json(['message' => 'Ward deleted successfully']);
    }
}
