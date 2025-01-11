<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CouponFilter extends ModelFilter
{
    /**
     * Filter coupons by search term in the name.
     */
    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('name', 'LIKE', "%$search%")
                     ->orWhere('code', 'LIKE', "%$search%"); // Search by name or code
        });
    }

    /**
     * Filter coupons by code.
     */
    public function code($code)
    {
        return $this->where('code', $code);
    }

    /**
     * Filter coupons by status.
     */
    public function status($status)
    {
        return $this->where('status', $status);
    }

    /**
     * Filter coupons by quantity.
     */
    public function quantity($quantity)
    {
        return $this->where('quantity', $quantity);
    }

    /**
     * Filter coupons by max usage per person.
     */
    public function maxUse($maxUse)
    {
        return $this->where('max_use', $maxUse);
    }

    /**
     * Filter coupons by discount type.
     */
    public function discountType($discountType)
    {
        return $this->where('discount_type', $discountType);
    }

    /**
     * Filter coupons by discount amount.
     */
    public function discount($discount)
    {
        return $this->where('discount', $discount);
    }

    /**
     * Filter coupons by start date.
     */
    public function startDate($startDate)
    {
        return $this->whereDate('start_date', '>=', $startDate);
    }

    /**
     * Filter coupons by end date.
     */
    public function endDate($endDate)
    {
        return $this->whereDate('end_date', '<=', $endDate);
    }

    
}
