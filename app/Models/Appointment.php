<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'AppointmentID';
    protected $table = 'appointments';

    protected $fillable = [
        'AppointmentDate',
        'AppointmentTime',
        'PatientID',
        'DoctorID',
        'Status'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID');
    }
}