<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $primaryKey = 'WardID';
    protected $fillable = [
        'WardTypeID',
        'WardName',
        'Capacity',
        'CurrentOccupancy',
        'DoctorID',
    ];

    public function wardType()
    {
        return $this->belongsTo(WardType::class, 'WardTypeID', 'WardTypeID');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID', 'DoctorID');
    }

    public function wardStaff()
    {
        return $this->hasMany(WardStaff::class, 'WardID', 'WardID');
    }

    /**
     * Get all beds in this ward
     */
    public function beds()
    {
        return $this->hasMany(WardBed::class, 'WardID', 'WardID');
    }

    /**
     * Get available beds in this ward
     */
    public function availableBeds()
    {
        return $this->hasMany(WardBed::class, 'WardID', 'WardID')
            ->where('Status', 'available');
    }

    /**
     * Get occupied beds in this ward
     */
    public function occupiedBeds()
    {
        return $this->hasMany(WardBed::class, 'WardID', 'WardID')
            ->where('Status', 'occupied');
    }

    /**
     * Get beds under maintenance in this ward
     */
    public function maintenanceBeds()
    {
        return $this->hasMany(WardBed::class, 'WardID', 'WardID')
            ->where('Status', 'maintenance');
    }
}
