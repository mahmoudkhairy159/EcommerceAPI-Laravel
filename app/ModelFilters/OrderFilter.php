<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class OrderFilter extends ModelFilter
{
/**
 * Filter by user_id.
 *
 * @param int $userId
 * @return $this
 */
    public function userId($userId)
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter by order_number.
     *
     * @param string $orderNumber
     * @return $this
     */
    public function orderNumber($orderNumber)
    {
        return $this->where('id', $orderNumber);
    }

    /**
     * Filter by order_date range.
     *
     * @param string $date
     * @return $this
     */
    public function fromOrderDate($fromOrderDate)
    {
        return $this->where(function ($q) use ($fromOrderDate) {
            return $q->whereDate('order_date', '>=', $fromOrderDate);
        });
    }

    /**
     * Filter by order_date range.
     *
     * @param string $toDate
     * @return $this
     */
    public function toOrderDate($toOrderDate)
    {
        return $this->where(function ($q) use ($toOrderDate) {
            return $q->whereDate('order_date', '<=', $toOrderDate);
        });
    }

    /**
     * Filter by status.
     *
     * @param string $status
     * @return $this
     */
    public function status($status)
    {
        return $this->where('status', $status);
    }

    /**
     * Filter by payment_method.
     *
     * @param string $paymentMethod
     * @return $this
     */
    public function paymentMethod($paymentMethod)
    {
        return $this->where('payment_method', $paymentMethod);
    }

    /**
     * Filter by total_price range.
     *
     * @param float $minTotalCost
     * @param float $maxTotalCost
     * @return $this
     */
    public function fromTotalPrice($fromTotalPrice)
    {

        return $this->where(function ($q) use ($fromTotalPrice) {
            return $q->where('total_price', '>=', $fromTotalPrice);
        });
    }

    public function toTotalPrice($toTotalPrice)
    {

        return $this->where(function ($q) use ($toTotalPrice) {
            return $q->where('total_price', '<=', $toTotalPrice);
        });
    }

    /**
     * Filter by tax.
     *
     * @param float $tax
     * @return $this
     */
    public function tax($tax)
    {
        return $this->where('tax', $tax);
    }

    /**
     * Filter by notes (search).
     *
     * @param string $notes
     * @return $this
     */
    public function notes($notes)
    {
        return $this->where('notes', 'like', "%$notes%");
    }

}
