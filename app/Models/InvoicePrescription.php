<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePrescription extends Model
{
    use HasFactory;

    protected $primaryKey = 'InvoicePrescriptionID';
    protected $fillable = [
        'InvoiceID',
        'PrescriptionID',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID', 'InvoiceID');
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'PrescriptionID', 'PrescriptionID');
    }
}
