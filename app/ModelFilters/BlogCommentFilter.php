<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class BlogCommentFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->where('content', 'LIKE', "%$search%");
        });
    }


}
