<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class DiscountTypeEnum extends Enum
{
    const PERCENTAGE = 'percentage';
    const  AMOUNT= 'amount';


    public static function getConstants(): array
    {
        $reflection = new \ReflectionClass(static::class);
        return $reflection->getConstants();
    }
}
