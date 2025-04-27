<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointment Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #3f8cff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .appointment-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            background-color: #3f8cff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .status-approved {
            color: #28a745;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Appointment Status Update</h1>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $appointment->patientUser->FullName ?? 'Patient' }}</strong>,</p>
        
        <p>Your appointment has been <span class="status-{{ $appointment->Status }}">{{ ucfirst($appointment->Status) }}</span> 
        @if($appointment->doctorUser)
        by Dr. {{ $appointment->doctorUser->FullName }}
        @endif
        .</p>
        
        <div class="appointment-details">
            <p><strong>Date:</strong> {{ date('F j, Y', strtotime($appointment->AppointmentDate)) }}</p>
            <p><strong>Time:</strong> {{ date('g:i A', strtotime($appointment->AppointmentTime)) }}</p>
            @if($appointment->doctorUser)
            <p><strong>Doctor:</strong> {{ $appointment->doctorUser->FullName }}</p>
            @endif
            <p><strong>Reason:</strong> {{ $appointment->Reason }}</p>
            <p><strong>Status:</strong> <span class="status-{{ $appointment->Status }}">{{ ucfirst($appointment->Status) }}</span></p>
            @if($appointment->DoctorNotes)
            <p><strong>Doctor's Notes:</strong> {{ $appointment->DoctorNotes }}</p>
            @endif
        </div>
        
        @if($appointment->Status == 'approved')
        <p>Please arrive 15 minutes before your scheduled appointment time. If you need to cancel or reschedule, please do so at least 24 hours in advance.</p>
        @else
        <p>If you have any questions about why your appointment was rejected or would like to schedule a different appointment, please contact us.</p>
        @endif
        
        <a href="{{ url('/patient/appointments') }}" class="btn">View Appointment</a>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} Hospital Management System. All rights reserved.</p>
        <p>This is an automated email, please do not reply.</p>
    </div>
</body>
</html> 