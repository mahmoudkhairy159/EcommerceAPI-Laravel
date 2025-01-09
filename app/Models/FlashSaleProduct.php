<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'flash_sale_id',
        'product_id',
        'show_at_home',
        'status'
    ];
    //status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    //status
    //Show in home
    const SHOW_AT_HOME_ACTIVE = 1;
    const SHOW_AT_HOME_INACTIVE = 0;
    //Show in home
    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
