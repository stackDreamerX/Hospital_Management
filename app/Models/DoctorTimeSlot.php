<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorTimeSlot extends Model
{
    use HasFactory;

    protected $table = 'doctor_time_slots';
    protected $primaryKey = 'id';

    protected $fillable = [
        'doctor_id',
        'date',          // YYYY-MM-DD format
        'time',          // HH:MM:SS format
        'status',        // 'available', 'booked', 'cancelled', 'completed'
        'appointment_id' // Optional link to appointment if booked
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'DoctorID');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'AppointmentID');
    }

    // Check if slot is available
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    // Check if slot is in the future
    public function isFuture()
    {
        $dateTime = $this->date . ' ' . $this->time;
        return strtotime($dateTime) > time();
    }

    // Format time in 12-hour format
    public function getFormattedTimeAttribute()
    {
        return date('h:i A', strtotime($this->time));
    }
}