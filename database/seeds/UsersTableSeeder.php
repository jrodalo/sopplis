<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Elisa',
            'email' => 'efalconlison@yahoo.es',
            'api_token' => str_random(60),
        ]);

        DB::table('users')->insert([
            'name' => 'Jose',
            'email' => 'jrodalo@gmail.com',
            'api_token' => str_random(60),
        ]);
    }
}
