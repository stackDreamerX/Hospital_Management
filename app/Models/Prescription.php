<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    // Đặt tên khóa chính
    protected $primaryKey = 'PrescriptionID';

    // Cấu hình tự tăng khóa chính
    public $incrementing = true;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'int';

    // Các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'PrescriptionDate',
        'UserID', // Liên kết đến bảng users
        'DoctorID', // Liên kết đến bảng doctors
        'TotalPrice',
    ];

    // Liên kết tới bảng users (bệnh nhân)
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    // Liên kết tới bảng doctors
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID', 'DoctorID');
    }

    // Liên kết tới bảng medicines (nếu có bảng lưu thuốc kê trong đơn thuốc)
    public function medicines()
    {
        return $this->hasMany(PrescriptionMedicine::class, 'PrescriptionID', 'PrescriptionID');
    }
}
