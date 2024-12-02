<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [

            [
                'name' => 'Mahmoud Khairy',
                'email' => 'mahmoudkhairy159@gmail.com',
                'password' => '12345678',
                'verified_at' => '2023-10-07 19:22:09',

            ],

        ];
        foreach ($items as $item) {
            $user = User::Create($item);
            $user->profile()->create();
        }

    }
}
