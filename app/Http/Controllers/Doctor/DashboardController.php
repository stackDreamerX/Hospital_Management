<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $doctorId = session('doctor_id', 1);

        // Get statistics
        $todayAppointments = 5;
        $activePatients = 25;
        $pendingLabs = 3;
        $activeTreatments = 8;

        // Get today's schedule
        $todaySchedule = [
            [
                'Time' => '09:00',
                'PatientName' => 'John Doe',
                'Type' => 'Consultation',
                'Status' => 'Pending',
                'Notes' => 'Regular checkup'
            ],
            [
                'Time' => '10:30',
                'PatientName' => 'Jane Smith',
                'Type' => 'Follow-up',
                'Status' => 'Completed',
                'Notes' => 'Post-surgery checkup'
            ],
            [
                'Time' => '10:30',
                'PatientName' => 'Jane Smith',
                'Type' => 'Follow-up',
                'Status' => 'Completed',
                'Notes' => 'Post-surgery checkup'
            ],
            [
                'Time' => '10:30',
                'PatientName' => 'Jane Smith',
                'Type' => 'Follow-up',
                'Status' => 'Completed',
                'Notes' => 'Post-surgery checkup'
            ],
            [
                'Time' => '10:30',
                'PatientName' => 'Jane Smith',
                'Type' => 'Follow-up',
                'Status' => 'Completed',
                'Notes' => 'Post-surgery checkup'
            ],
            [
                'Time' => '10:30',
                'PatientName' => 'Jane Smith',
                'Type' => 'Follow-up',
                'Status' => 'Completed',
                'Notes' => 'Post-surgery checkup'
            ],
            [
                'Time' => '10:30',
                'PatientName' => 'Jane Smith',
                'Type' => 'Follow-up',
                'Status' => 'Completed',
                'Notes' => 'Post-surgery checkup'
            ],
            [
                'Time' => '10:30',
                'PatientName' => 'Jane Smith',
                'Type' => 'Follow-up',
                'Status' => 'Completed',
                'Notes' => 'Post-surgery checkup'
            ],
            [
                'Time' => '10:30',
                'PatientName' => 'Jane Smith',
                'Type' => 'Follow-up',
                'Status' => 'Completed',
                'Notes' => 'Post-surgery checkup'
            ]
        ];

        // Get recent activities
        $recentActivities = [
            [
                'Type' => 'appointment',
                'Description' => 'New appointment with John Doe',
                'Time' => '1 hour ago'
            ],
            [
                'Type' => 'lab',
                'Description' => 'Lab results received for Jane Smith',
                'Time' => '2 hours ago'
            ],
            [
                'Type' => 'treatment',
                'Description' => 'Treatment completed for Mike Johnson',
                'Time' => '3 hours ago'
            ]
        ];

        // Get pending tasks
        $pendingTasks = [
            [
                'Title' => 'Review Lab Results',
                'Description' => 'Blood test results for Patient #123',
                'Type' => 'Lab Review',
                'DueDate' => 'Today'
            ],
            [
                'Title' => 'Update Treatment Plan',
                'Description' => 'Update recovery plan for Patient #456',
                'Type' => 'Treatment',
                'DueDate' => 'Tomorrow'
            ]
        ];

        return view('doctor.dashboard', compact(
            'todayAppointments',
            'activePatients',
            'pendingLabs',
            'activeTreatments',
            'todaySchedule',
            'recentActivities',
            'pendingTasks'
        ));
    }
} 