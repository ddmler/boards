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
        User::create([
          'name' => 'admin',
          'email' => 'admin@example.com',
          'password' => bcrypt('admin')
        ]);
    }
}
