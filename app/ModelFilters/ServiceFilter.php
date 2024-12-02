<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ServiceFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('name', 'LIKE', "%$search%")
                ->orWhere('code', 'LIKE', "%$search%");
        });
    }
    public function code($code)
    {
        return $this->where(function ($q) use ($code) {
            return $q->where('code', $code);
        });
    }
    public function categoryId($categoryId)
    {
        return $this->where(function ($q) use ($categoryId) {
            return $q->where('category_id', $categoryId);
        });
    }
    public function mainCategory($mainCategory)
    {
        return $this->where(function ($q) use ($mainCategory) {
            return $q->where('main_category', $mainCategory);
        });
    }
    public function categoryName($categoryName)
    {
        return $this->where(function ($q) use ($categoryName) {
            return $q->whereHas("category", function ($q) use ($categoryName) {
                return $q->where("name", "like", "%" . $categoryName . "%");
            });
        });
    }
    public function status($status)
    {
        return $this->where(function ($q) use ($status) {
            return $q->where('status', $status);
        });
    }

}
