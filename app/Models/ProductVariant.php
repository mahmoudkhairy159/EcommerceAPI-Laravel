<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'name',
        'status'
    ];
    public $timestamps = false;

    //status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    //status

    //relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function productVariantItems()
    {
        return $this->hasMany(ProductVariantItem::class);
    }
    //relationships}
}
