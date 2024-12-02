<?php

namespace App\ModelFilters;

use App\Models\Product;
use App\Models\Service;
use EloquentFilter\ModelFilter;

class ReviewFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('comment', 'LIKE', "%$search%");
        });
    }
    public function rate($rate)
    {
        return $this->where(function ($q) use ($rate) {
            return $q->where('rate', $rate);
        });
    }
    public function productId($productId)
    {
        return $this->where(function ($q) use ($productId) {
            return $q->where('reviewable',Product::class)->where('reviewable_id', $productId);
        });
    }
    public function serviceId($serviceId)
    {
        return $this->where(function ($q) use ($serviceId) {
            return $q->where('reviewable',Service::class)->where('reviewable_id', $serviceId);
        });
    }
    public function userId($userId)
    {
        return $this->where(function ($q) use ($userId) {
            return $q->where('user_id', $userId);
        });
    }

    public function status($status)
    {
        return $this->where(function ($q) use ($status) {
            return $q->where('status', $status);
        });
    }

}
