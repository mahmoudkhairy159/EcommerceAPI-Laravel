<?php

namespace App\ModelFilters;

use App\Models\Cart;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\Auth;

class ProductFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('name', 'LIKE', "%$search%")
                ->where('short_description', 'LIKE', "%$search%")
                ->where('description', 'LIKE', "%$search%")
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
    public function brandId($brandId)
    {
        return $this->where(function ($q) use ($brandId) {
            return $q->where('brand_id', $brandId);
        });
    }
    public function status($status)
    {
        return $this->where(function ($q) use ($status) {
            return $q->where('status', $status);
        });
    }
    public function isFeatured($isFeatured)
    {
        return $this->where(function ($q) use ($isFeatured) {
            return $q->where('is_featured', $isFeatured);
        });
    }
    public function orderType($orderType)
    {
        return $this->where(function ($q) use ($orderType) {
            return $q->where('order_type', $orderType);
        });
    }
   
    public function returnPolicy($returnPolicy)
    {
        return $this->where(function ($q) use ($returnPolicy) {
            return $q->where('return_policy', 'LIKE', "%$returnPolicy%");
        });
    }
    public function createdAt($createdAt)
    {
        return $this->where(function ($q) use ($createdAt) {
            return $q->where('created_at', $createdAt);
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
    public function brandName($brandName)
    {
        return $this->where(function ($q) use ($brandName) {
            return $q->whereHas("brand", function ($q) use ($brandName) {
                return $q->where("name", "like", "%" . $brandName . "%");
            });
        });
    }
    public function latest($latest)
    {
        if ($latest) {
            return $this->orderBy("created_at", "DESC");
        }
        return $this;
    }
    public function rank($rank)
    {
        if ($rank) {
            return $this->orderBy("created_at", "DESC");
        }
        return $this;
    }

    public function offers($offers)
    {
        if ($offers) {
            return $this->where("discount", ">", 0);
        }
        return $this;
    }

    public function recommended($recommended)
    {
        if ($recommended) {
            $cart_products_ids = Cart::where("user_id", Auth::guard('user-api')->id())->pluck("product_id")->toArray();
            $cart_categories_id = $this->whereIn("id", $cart_products_ids)->pluck("category_id");
            if (sizeof($cart_categories_id) == 0) {
                return $this;
            }
            return $this->whereIn("category_id", $cart_categories_id)->inRandomOrder();
        }
        return $this;
    }

    public function fromSellingPrice($fromSellingPrice)
    {
        if (request()->toSellingPrice === null) {
            return $this->where(function ($q) use ($fromSellingPrice) {
                return $q->where('selling_price', $fromSellingPrice);
            });
        }
        return $this->where(function ($q) use ($fromSellingPrice) {
            return $q->where('selling_price', '>=', $fromSellingPrice);
        });
    }
    public function toSellingPrice($toSellingPrice)
    {
        if (request()->fromSellingPrice === null) {
            return $this->where(function ($q) use ($toSellingPrice) {
                return $q->where('selling_price', $toSellingPrice);
            });
        }
        return $this->where(function ($q) use ($toSellingPrice) {
            return $q->where('selling_price', '<=', $toSellingPrice);
        });
    }

    public function fromCostPrice($fromCostPrice)
    {
        if (request()->toCostPrice === null) {
            return $this->where(function ($q) use ($fromCostPrice) {
                return $q->where('cost_price', $fromCostPrice);
            });
        }
        return $this->where(function ($q) use ($fromCostPrice) {
            return $q->where('cost_price', '>=', $fromCostPrice);
        });
    }
    public function toCostPrice($toCostPrice)
    {
        if (request()->fromCostPrice === null) {
            return $this->where(function ($q) use ($toCostPrice) {
                return $q->where('cost_price', $toCostPrice);
            });
        }
        return $this->where(function ($q) use ($toCostPrice) {
            return $q->where('cost_price', '<=', $toCostPrice);
        });
    }
}
