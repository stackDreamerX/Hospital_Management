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
        'UserID',
        'DoctorNotes',
        'DoctorID',
        'Status',
        'payment_method',
        'payment_status',
        'payment_id',
        'amount',
        'payment_details',
    ];

    protected $casts = [
        'payment_details' => 'array',
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
