<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory,Filterable;
       // Fillable attributes
       protected $fillable = [
        'order_id',
        'user_id',
        'transaction_id',
        'amount',
        'payment_status',
        'payment_method',
    ];
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

    public static function getPaymentMethods()
    {
        return [
            self::PAYMENT_METHOD_CASH,
            self::PAYMENT_METHOD_CREDIT_CARD,
            self::PAYMENT_METHOD_PAYPAL,
            self::PAYMENT_METHOD_BANK_TRANSFER,
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

    // Relationships

    /**
     * Get the order associated with the transaction.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who initiated the transaction (if applicable).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
