<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointment Cancelled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #dc3545;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Appointment Cancelled</h1>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $appointment->user->FullName }}</strong>,</p>
        
        <p>Your appointment has been cancelled as requested. Here are the details of the cancelled appointment:</p>
        
        <div class="appointment-details">
            <p><strong>Date:</strong> {{ date('F j, Y', strtotime($appointment->AppointmentDate)) }}</p>
            <p><strong>Time:</strong> {{ date('g:i A', strtotime($appointment->AppointmentTime)) }}</p>
            <p><strong>Doctor:</strong> {{ $appointment->doctor->user->FullName }}</p>
            <p><strong>Reason:</strong> {{ $appointment->Reason }}</p>
        </div>
        
        <p>If you wish to schedule a new appointment, please log in to your patient portal or contact us directly.</p>
        
        <a href="{{ url('/patient/appointments') }}" class="btn">Schedule New Appointment</a>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} Hospital Management System. All rights reserved.</p>
        <p>This is an automated email, please do not reply.</p>
    </div>
</body>
</html> 