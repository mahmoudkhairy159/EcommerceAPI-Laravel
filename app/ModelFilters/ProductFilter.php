<?php
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ProductFilter extends ModelFilter
{
    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%$search%")
                ->orWhere('short_description', 'LIKE', "%$search%")
                ->orWhere('long_description', 'LIKE', "%$search%")
                ->orWhere('code', 'LIKE', "%$search%");
        });
    }

    public function code($code)
    {
        return $this->where('code', $code);
    }

    public function categoryId($categoryId)
    {
        return $this->where('category_id', $categoryId);
    }

    public function brandId($brandId)
    {
        return $this->where('brand_id', $brandId);
    }

    public function status($status)
    {
        return $this->where('status', $status);
    }

    public function isFeatured($isFeatured)
    {
        return $this->where('is_featured', $isFeatured);
    }

    public function isBest($isBest)
    {
        return $this->where('is_best', $isBest);
    }

    public function isTop($isTop)
    {
        return $this->where('is_top', $isTop);
    }

    public function approvalStatus($statusApproval)
    {
        return $this->where('approval_status', $statusApproval);
    }

    public function returnPolicy($returnPolicy)
    {
        return $this->where('return_policy', 'LIKE', "%$returnPolicy%");
    }

    public function createdAt($createdAt)
    {
        return $this->where('created_at', $createdAt);
    }

    public function categoryName($categoryName)
    {
        return $this->whereHas('category', function ($q) use ($categoryName) {
            $q->where('name', 'like', "%$categoryName%");
        });
    }

    public function brandName($brandName)
    {
        return $this->whereHas('brand', function ($q) use ($brandName) {
            $q->where('name', 'like', "%$brandName%");
        });
    }

    public function latest()
    {
        return $this->orderBy('created_at', 'DESC');
    }

    public function serial()
    {
        return $this->orderBy('serial', 'asc');
    }

    public function offers($offers)
    {
        if ($offers) {
            return $this->where('offer_price', '>', 0)
                ->whereDate('offer_start_date', '<=', now())
                ->whereDate('offer_end_date', '>=', now());
        }
        return $this;
    }

    public function fromPrice($fromPrice)
    {
        return $this->where('price', '>=', $fromPrice);
    }

    public function toPrice($toPrice)
    {
        return $this->where('price', '<=', $toPrice);
    }

}
