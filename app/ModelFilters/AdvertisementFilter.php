<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class AdvertisementFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('title', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%")
                ->orWhere('url', 'LIKE', "%$search%");
        });
    }


    public function status($status)
    {
        return $this->where(function ($q) use ($status) {
            return $q->where('status', $status);
        });
    }
    public function position($position)
    {
        return $this->where('position', $position);
    }


    public function clicks($clicks)
    {
        return $this->where('clicks','>=' ,$clicks);
    }

}
