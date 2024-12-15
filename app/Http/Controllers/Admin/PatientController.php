<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    private $samplePatients = [
        [
            'PatientID' => 1,
            'UserID' => 1,
            'FullName' => 'John Doe',
            'Username' => 'john.doe',
            'Email' => 'john.doe@example.com',
            'PhoneNumber' => '0123456789',
            'DateOfBirth' => '1990-05-15',
            'Gender' => 'male',
            'Address' => '123 Main Street, City'
        ],
        [
            'PatientID' => 2,
            'UserID' => 2,
            'FullName' => 'Jane Smith',
            'Username' => 'jane.smith',
            'Email' => 'jane.smith@example.com',
            'PhoneNumber' => '0123456788',
            'DateOfBirth' => '1985-08-22',
            'Gender' => 'female',
            'Address' => '456 Oak Avenue, Town'
        ],
        [
            'PatientID' => 3,
            'UserID' => 3,
            'FullName' => 'Alex Johnson',
            'Username' => 'alex.johnson',
            'Email' => 'alex.johnson@example.com',
            'PhoneNumber' => '0123456787',
            'DateOfBirth' => '1995-12-10',
            'Gender' => 'other',
            'Address' => '789 Pine Road, Village'
        ],
        [
            'PatientID' => 4,
            'UserID' => 4,
            'FullName' => 'Sarah Williams',
            'Username' => 'sarah.williams',
            'Email' => 'sarah.williams@example.com',
            'PhoneNumber' => '0123456786',
            'DateOfBirth' => '1988-03-30',
            'Gender' => 'female',
            'Address' => '321 Elm Street, County'
        ],
        [
            'PatientID' => 5,
            'UserID' => 5,
            'FullName' => 'Michael Brown',
            'Username' => 'michael.brown',
            'Email' => 'michael.brown@example.com',
            'PhoneNumber' => '0123456785',
            'DateOfBirth' => '1992-07-18',
            'Gender' => 'male',
            'Address' => '654 Maple Drive, District'
        ]
    ];

    public function patient()
    {
        $patients = collect($this->samplePatients);
        
        // Sort patients by ID in descending order (newest first)
        $patients = $patients->sortByDesc('PatientID');

        return view('admin.patient', compact('patients'));
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'fullname' => 'required|string|max:100',
            'username' => 'required|string|regex:/^[a-zA-Z0-9._-]{3,50}$/',
            'email' => 'required|email|max:100',
            'phone' => 'required|regex:/^0[0-9]{9}$/',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ]
        ]);

        // In a real application, you would save to database here
        return response()->json(['message' => 'Patient created successfully']);
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'fullname' => 'required|string|max:100',
            'username' => 'required|string|regex:/^[a-zA-Z0-9._-]{3,50}$/',
            'email' => 'required|email|max:100',
            'phone' => 'required|regex:/^0[0-9]{9}$/',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ]
        ]);

        // In a real application, you would update the database here
        return response()->json(['message' => 'Patient updated successfully']);
    }

    public function destroy($id)
    {
        // In a real application, you would delete from database here
        return response()->json(['message' => 'Patient deleted successfully']);
    }
}
