<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Enums\ShippingRuleTypeEnum;
use App\Models\ShippingRule;

class ShippingRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shippingRules = [
            [
                'name' => 'Free Shipping',
                'type' => ShippingRuleTypeEnum::MIN_COST, // Enum constant for 'min_cost'
                'min_cost' =>1000,  //minimum order amount
                'cost' => 0,
                'status' =>ShippingRule::STATUS_ACTIVE,
            ],
            [
                'name' => 'Express Delivery',
                'type' => ShippingRuleTypeEnum::FLAT_COST, // Enum constant for 'flat_cost'
                'min_cost' => 0, // Minimum order cost for eligibility
                'cost' => 20, // Fixed cost for this shipping rule
                'status' => ShippingRule::STATUS_ACTIVE,

            ],
        ];

        DB::table('shipping_rules')->insert($shippingRules);
    }
}
