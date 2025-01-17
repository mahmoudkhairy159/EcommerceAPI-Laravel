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
        'vendor_id',
        'status',
        'payment_method',
        'payment_status',
        'sub_total',
        'discount_amount',
        'amount',
        'order_address',
        'shipping_rule',
        'coupon',
        'notes',
    ];
    // Cast attributes
    protected $casts = [
        'order_address' => 'array',
        'shipping_rule' => 'array',
        'coupon' => 'array',
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
// Enum values for payment_status
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_FAILED = 'failed';
    // Enum values for payment_status
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
    public static function getPaymentStatuses()
    {
        return [
            self::PAYMENT_STATUS_PAID,
            self::PAYMENT_STATUS_PENDING,
            self::PAYMENT_STATUS_FAILED,
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

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot('price','tax', 'quantity','variants','variantsTotalPrice')
                    ->withTimestamps();
    }

}
