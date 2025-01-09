<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;
    protected $fillable = [
        'end_date',
        'created_by',
        'updated_by'
    ];
    public function flashSaleProducts()
    {
        return $this->hasMany(FlashSaleProduct::class);
    }
    
}
