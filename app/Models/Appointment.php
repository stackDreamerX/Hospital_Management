<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'AppointmentID';
    protected $fillable = [
        'AppointmentDate',
        'AppointmentTime',
        'Reason',
        'Symptoms',
        'Notes',
        'PatientID',
        'DoctorID',
        'Status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}
