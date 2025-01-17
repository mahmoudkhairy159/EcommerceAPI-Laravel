<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Enums\DiscountTypeEnum;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coupons = [
            [
                'name' => 'Summer Sale',
                'code' => 'SUMMER2025',
                'quantity' => 100,
                'total_used' => 0,
                'max_use' => 1,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'discount_type' => DiscountTypeEnum::PERCENTAGE, // Enum constant for percentage discount
                'discount' => 20, // 20% discount
                'status' => 1,

            ],
            [
                'name' => 'Flat Discount',
                'code' => 'FLAT50',
                'quantity' => 50,
                'total_used' => 0,
                'max_use' => 2,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(10),
                'discount_type' => DiscountTypeEnum::AMOUNT, // Enum constant for fixed amount discount
                'discount' => 50, // Flat $50 discount
                'status' => 1,
            ],
        ];

        DB::table('coupons')->insert($coupons);
    }
}
