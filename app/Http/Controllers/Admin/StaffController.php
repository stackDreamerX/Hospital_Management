<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    private $sampleDoctors = [
        [
            'DoctorID' => 1,
            'UserID' => 1,
            'FullName' => 'Dr. Sarah Wilson',
            'Username' => 'dr.wilson',
            'Email' => 'sarah.wilson@example.com',
            'PhoneNumber' => '0123456789',
            'Speciality' => 'Cardiology',
            'Title' => 'Senior Consultant'
        ],
        [
            'DoctorID' => 2,
            'UserID' => 2,
            'FullName' => 'Dr. Michael Brown',
            'Username' => 'dr.brown',
            'Email' => 'michael.brown@example.com',
            'PhoneNumber' => '0123456788',
            'Speciality' => 'Neurology',
            'Title' => 'Specialist'
        ]
    ];

    public function staff()
    {
        $doctors = collect($this->sampleDoctors);
        return view('admin.staff', compact('doctors'));
    }
}
