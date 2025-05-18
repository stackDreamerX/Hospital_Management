<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $primaryKey = 'DoctorID';
    protected $table = 'doctors';

    protected $fillable = [
        'UserID',
        'Speciality',
        'Title',
        'WorkLocation',
        'AvailableHours',
        'pricing_vn',    // Price for Vietnamese patients
        'pricing_foreign' // Price for foreign patients
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'DoctorID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID'); // Adjust 'user_id' as necessary
    }

    /**
     * Get ratings for this doctor
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'doctor_id', 'DoctorID');
    }

    /**
     * Get doctor schedules
     */
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id', 'DoctorID');
    }

    /**
     * Get doctor time slots
     */
    public function timeSlots()
    {
        return $this->hasMany(DoctorTimeSlot::class, 'doctor_id', 'DoctorID');
    }

    /**
     * Get average doctor rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->where('status', 'approved')
            ->whereNotNull('doctor_rating')
            ->avg('doctor_rating');
    }
}