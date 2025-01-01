<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceivedNote extends Model
{
    use HasFactory;

    protected $primaryKey = 'GRNID';
    protected $fillable = [
        'ReceivedTime',
        'ProviderID',
        'MedicineID',
        'UnitPrice',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'ProviderID', 'ProviderID');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'MedicineID', 'MedicineID');
    }
}
