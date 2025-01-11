<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use App\Enums\ShippingRuleTypeEnum;

class ShippingRuleFilter extends ModelFilter
{
    /**
     * Filter shipping rules by search term in the name.
     */
    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('name', 'LIKE', "%$search%");
        });
    }

    /**
     * Filter shipping rules by status.
     */
    public function status($status)
    {
        return $this->where('status', $status);
    }

    /**
     * Filter shipping rules by type (using the ShippingRuleTypeEnum).
     */
    public function type($type)
    {
        return $this->where('type', $type);
    }

    /**
     * Filter shipping rules by minimum cost.
     */
    public function minCost($minCost)
    {
        return $this->where('min_cost', '>=', $minCost);
    }

    /**
     * Filter shipping rules by cost.
     */
    public function cost($cost)
    {
        return $this->where('cost', '>=', $cost);
    }



    
}
