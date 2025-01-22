<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class BlogFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('title', 'LIKE', "%$search%")
                ->orWhere('body', 'LIKE', "%$search%");
        });
    }
    public function code($code)
    {
        return $this->where(function ($q) use ($code) {
            return $q->where('code', $code);
        });
    }



    public function status($status)
    {
        return $this->where(function ($q) use ($status) {
            return $q->where('status', $status);
        });
    }

}
