<?php

namespace App\Traits;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

trait ProductCalculationTrait
{

    public function checkDiscount(Product $product)
    {
        $currentDate=date('y-m-d');
        if ($product->offer_price>0 &&  $currentDate>=$product->offer_start_date &&  $currentDate<=$product->offer_end_date ){
            return true;
        }
        return false;

    }
    public function calculateDiscountPercent($originalPrice,$discountPrice)
    {
        $discountAmount=$originalPrice-$discountPrice;
        $discountPercent=($discountAmount/$originalPrice)*100;

    }


    public function calculateTax($price, $quantity)
    {
        $tax = core()->getAppSettings()->tax_percentage;
        return ($tax * $price * $quantity) / 100;
    }

    // Calculate subtotal
    public function calculateSubtotal($price, $totalTax, $quantity, $variantsTotalPrice)
    {
        return (($price+$variantsTotalPrice) * $quantity) + $totalTax ;
    }



}
