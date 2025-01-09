<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FlashSale::create([
            'end_date' => now()->addDays(7), 
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
