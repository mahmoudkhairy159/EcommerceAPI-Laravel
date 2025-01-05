<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendor =   Vendor::create([
            'name' => 'Mahmoud Khairy',
            'email' => 'mahmoudkhairy159@gmail.com',
            'password' => '12345678',
            'status' => 1,
        ]);
    

    }
}
