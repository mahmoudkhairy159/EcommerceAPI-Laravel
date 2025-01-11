<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class ShippingRuleTypeEnum extends Enum
{
    const FLAT_COST = 'flat_cost';
    const  MIN_COST= 'min_cost';


    public static function getConstants(): array
    {
        $reflection = new \ReflectionClass(static::class);
        return $reflection->getConstants();
    }
}
