<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDetail extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'prescription_details';

    // Khóa chính của bảng
    protected $primaryKey = 'PrescriptionDetailID';

    // Các trường có thể được gán giá trị bằng cách sử dụng phương thức create hoặc fill
    protected $fillable = [
        'PrescriptionID',
        'MedicineID',
        'Quantity',
        'Price',
        'Dosage',
        'Frequency',
        'Duration',
    ];

    // Quan hệ với bảng Prescription
    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'PrescriptionID', 'PrescriptionID');
    }

    // Quan hệ với bảng Medicine
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'MedicineID', 'MedicineID');
    }
}
