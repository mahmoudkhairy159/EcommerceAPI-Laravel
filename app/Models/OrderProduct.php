<?php

namespace App\Models;

use App\ModelFilters\OrderProductFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{

    use HasFactory, Filterable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'selling_price',
        'cost_price',
        'return_policy',
        'discount',
    ];
    public function modelFilter()
    {
        return $this->provideFilter(OrderProductFilter::class);
    }
    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the item that owns the order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
