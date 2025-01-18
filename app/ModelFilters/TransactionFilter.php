<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class TransactionFilter extends ModelFilter
{



 /**
     * Filter by order ID.
     *
     * @param int $orderId
     * @return $this
     */
    public function orderId($orderId)
    {
        return $this->where('order_id', $orderId);
    }

    /**
     * Filter by user ID.
     *
     * @param int $userId
     * @return $this
     */
    public function userId($userId)
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter by transaction ID.
     *
     * @param string $transactionId
     * @return $this
     */
    public function transactionId($transactionId)
    {
        return $this->where('transaction_id', $transactionId);
    }

    /**
     * Filter by amount.
     *
     * @param float $amount
     * @return $this
     */
    public function amount($amount)
    {
        return $this->where('amount', $amount);
    }

    /**
     * Filter by payment status.
     *
     * @param string $paymentStatus
     * @return $this
     */
    public function paymentStatus($paymentStatus)
    {
        return $this->where('payment_status', $paymentStatus);
    }

    /**
     * Filter by payment method.
     *
     * @param string $paymentMethod
     * @return $this
     */
    public function paymentMethod($paymentMethod)
    {
        return $this->where('payment_method', $paymentMethod);
    }
}
