<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class AdminFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('name', 'LIKE', "%$search%")
                ->orWhere('phone', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%");
        });
    }

    public function status($status)
    {
        return $this->where(function ($q) use ($status) {
            return $q->where('status', $status);
        });
    }
}
