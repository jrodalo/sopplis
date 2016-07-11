<?php

use Illuminate\Database\Seeder;

class CartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carts')->insert([
            'name' => 'Lista de la compra',
            'slug' => '43f37d',
        ]);
    }
}
