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
        ]);

        DB::table('users')->insert([
            'name' => 'Jose',
            'email' => 'jrodalo@gmail.com',
        ]);
    }
}
