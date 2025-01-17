<?php

namespace App\Models;

use App\ModelFilters\OrderProductFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{

    use HasFactory, Filterable;
    protected $fillable = [
        'order_id',
        'vendor_id',
        'product_id',
        'price',
        'tax',
        'quantity',
        'variants',
        'variantsTotalPrice'
    ];
    protected $casts = [
        'variants' => 'array',
    ];
    // Relationships

    /**
     * Get the order that owns the order product.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the vendor that supplied the product.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the product details.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
