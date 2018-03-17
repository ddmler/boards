<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(BoardsTableSeeder::class);
        $this->call(BoardListsTableSeeder::class);
        $this->call(CardsTableSeeder::class);
    }
}
