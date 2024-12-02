<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ReferenceCustomerFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('name', 'LIKE', "%$search%");
        });
    }


}
