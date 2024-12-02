<?php

namespace App\Models;

use App\ModelFilters\OrderFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, Filterable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'order_date',
        'status',
        'payment_method',
        'discount_amount',
        'total_price',
        'tax',
        'notes',
        'tracking_id',
        'order_type',
        'state',
        'city',
        'pin_code',
        'billing_address',
        'order_phone_number',
    ];

    public function modelFilter()
    {
        return $this->provideFilter(OrderFilter::class);
    }
    // Enum values for status
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    // Enum values for status

    // Enum values for payment_method
    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_CREDIT_CARD = 'credit_card';
    const PAYMENT_METHOD_PAYPAL = 'paypal';
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
    // Enum values for payment_method

    /**
     * Get the list of valid statuses.
     *
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPING,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    /**
     * Get the list of valid payment methods.
     *
     * @return array
     */
    public static function getPaymentMethods()
    {
        return [
            self::PAYMENT_METHOD_CASH,
            self::PAYMENT_METHOD_CREDIT_CARD,
            self::PAYMENT_METHOD_PAYPAL,
            self::PAYMENT_METHOD_BANK_TRANSFER,
        ];
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity', 'selling_price', 'cost_price', 'return_policy','discount' );
    }

}
