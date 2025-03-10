<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CountryFilter extends ModelFilter
{

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q->whereTranslationLike('name', "%$search%")->orWhere('code', 'LIKE', "%$search%");
        });
    }
    public function code($code)
    {
        return $this->where(function ($q) use ($code) {
            return $q->where('code', $code);
        });
    }

    public function name($name)
    {
        return $this->where(function ($q) use ($name) {
            return $q->whereTranslationLike('name',"%$name%");
        });
    }
}
