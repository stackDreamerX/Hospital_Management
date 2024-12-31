<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'medicines';

    // Khóa chính của bảng
    protected $primaryKey = 'MedicineID';

    // Các trường có thể được gán giá trị bằng cách sử dụng phương thức create hoặc fill
    protected $fillable = [
        'MedicineName',
        'ExpiryDate',
        'ManufacturingDate',
        'UnitPrice',
    ];

    // Quan hệ với PrescriptionDetails
    public function prescriptionDetails()
    {
        return $this->hasMany(PrescriptionDetail::class, 'MedicineID', 'MedicineID');
    }
}
