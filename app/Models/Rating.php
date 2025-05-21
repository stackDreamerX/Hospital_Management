<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_id',
        'doctor_rating',
        'service_rating',
        'cleanliness_rating',
        'staff_rating',
        'wait_time_rating',
        'feedback',
        'is_anonymous',
        'status'
    ];

    /**
     * Get the user who created the rating
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'UserID');
    }

    /**
     * Get the doctor being rated
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'DoctorID');
    }

    /**
     * Get the appointment related to this rating
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Calculate the overall rating
     */
    public function getOverallRatingAttribute()
    {
        $ratings = [
            $this->doctor_rating,
            $this->service_rating,
            $this->cleanliness_rating,
            $this->staff_rating,
            $this->wait_time_rating
        ];

        // Filter out null values
        $validRatings = array_filter($ratings, function ($value) {
            return !is_null($value);
        });

        if (count($validRatings) === 0) {
            return null;
        }

        return round(array_sum($validRatings) / count($validRatings), 1);
    }
}