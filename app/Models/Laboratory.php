<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    protected $table = 'laboratories';
    protected $primaryKey = 'LaboratoryID';
    protected $fillable = [
        'LaboratoryTypeID',
        'PatientID',
        'DoctorID',
        'LaboratoryDate',
        'LaboratoryTime',
        'TotalPrice',
        'Status',
    ];

    public function laboratoryType()
    {
        return $this->belongsTo(LaboratoryType::class, 'LaboratoryTypeID', 'LaboratoryTypeID');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID', 'PatientID');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID', 'DoctorID');
    }

    public function laboratoryDetails()
    {
        return $this->hasMany(LaboratoryDetail::class, 'LaboratoryID', 'LaboratoryID');
    }
}
