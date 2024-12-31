<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineStock extends Model
{
    use HasFactory;

    protected $table = 'medicine_stock'; // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'id'; // Khóa chính
    public $incrementing = true; // Tự động tăng
    protected $keyType = 'int'; // Kiểu của khóa chính

    protected $fillable = [
        'MedicineID',
        'Quantity',
    ];

    /**
     * Relationship with the Medicine model
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'MedicineID', 'MedicineID');
    }
}
