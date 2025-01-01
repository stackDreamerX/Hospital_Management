<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTreatment extends Model
{
    use HasFactory;

    protected $primaryKey = 'InvoiceTreatmentID';
    protected $fillable = [
        'InvoiceID',
        'TreatmentID',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID', 'InvoiceID');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'TreatmentID', 'TreatmentID');
    }
}
