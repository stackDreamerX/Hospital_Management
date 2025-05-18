<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $table = 'doctor_schedules';
    protected $primaryKey = 'id';

    protected $fillable = [
        'doctor_id',
        'day_of_week',  // 1=Monday, 2=Tuesday, etc.
        'start_time',   // Format: HH:MM:SS
        'end_time',     // Format: HH:MM:SS
        'is_active'
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'DoctorID');
    }

    // Scope to get active schedules
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get available time slots (30 min intervals)
    public function getTimeSlots()
    {
        $slots = [];
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);

        // Generate slots in 30-minute intervals
        for ($time = $start; $time < $end; $time += 30*60) {
            $slots[] = date('H:i', $time);
        }

        return $slots;
    }
}