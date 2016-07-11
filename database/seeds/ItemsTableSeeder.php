<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
        	'cart_id' => 1,
            'name' => 'Tomates',
            'count' => 10,
            'visible' => 1,
            'done' => 0,
        ]);

        DB::table('items')->insert([
        	'cart_id' => 1,
            'name' => 'Pan de molde',
            'count' => 1,
            'visible' => 0,
            'done' => 1,
        ]);

        DB::table('items')->insert([
        	'cart_id' => 1,
            'name' => 'Mermelada',
            'count' => 5,
            'visible' => 1,
            'done' => 0,
        ]);

    }
}
