<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'name' => 'Mahmoud Khairy',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
            'status' => 1,
        ]);
        $admin->addRole('admin');
        //
        // $allPermissions = Permission::pluck('name')->toArray();
        // $admin->givePermissions($allPermissions);

    }
}
