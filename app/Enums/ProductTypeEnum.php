<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class ProductTypeEnum extends Enum
{
    const NEW_ARRIVAL = 'new_arrival';
    const  FEATURED_PRODUCT= 'featured_product';
    const TOP_PRODUCT = 'top_product';
    const BEST_PRODUCT = 'best_product';


    public static function getConstants(): array
    {
        $reflection = new \ReflectionClass(static::class);
        return $reflection->getConstants();
    }
}
