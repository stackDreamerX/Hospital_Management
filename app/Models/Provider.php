<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $primaryKey = 'ProviderID';
    protected $fillable = [
        'ProviderName',
    ];

    public function goodsReceivedNotes()
    {
        return $this->hasMany(GoodsReceivedNote::class, 'ProviderID', 'ProviderID');
    }
}

