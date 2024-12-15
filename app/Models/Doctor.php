<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $primaryKey = 'DoctorID';
    protected $table = 'doctors';

    protected $fillable = [
        'Name',
        'Specialization',
        'Email',
        'Phone'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'DoctorID');
    }
} 