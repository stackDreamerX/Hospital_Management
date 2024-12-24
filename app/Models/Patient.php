<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $primaryKey = 'PatientID';
    protected $fillable = ['UserID', 'DateOfBirth', 'Gender', 'Address'];

    public function laboratories()
    {
        return $this->hasMany(Laboratory::class, 'PatientID', 'PatientID');
    }
}
