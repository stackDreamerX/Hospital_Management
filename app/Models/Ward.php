<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'wards';

    // Khóa chính
    protected $primaryKey = 'WardID';

    // Các cột có thể được gán giá trị
    protected $fillable = [
        'WardTypeID',
        'WardName',
        'Capacity',
        'CurrentOccupancy',
        'DoctorID',
    ];

    /**
     * Quan hệ với bảng WardType
     */
    // public function wardType()
    // {
    //     return $this->belongsTo(WardType::class, 'WardTypeID', 'WardTypeID');
    // }

    /**
     * Quan hệ với bảng Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID', 'DoctorID');
    }

    /**
     * Quan hệ với bảng WardBed (một Ward có nhiều WardBed)
     */
    // public function beds()
    // {
    //     return $this->hasMany(WardBed::class, 'WardID', 'WardID');
    // }
}
