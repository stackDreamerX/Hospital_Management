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
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'DoctorID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID'); // Adjust 'user_id' as necessary
    }
}