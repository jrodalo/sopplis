<?php

use Illuminate\Database\Seeder;

class CartsUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cart_user')->insert([
            'user_id' => 1,
            'cart_id' => 1,
            'role' => 'owner',
        ]);

        DB::table('cart_user')->insert([
            'user_id' => 2,
            'cart_id' => 1,
            'role' => 'guest',
        ]);

    }
}
