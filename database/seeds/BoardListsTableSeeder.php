<?php

use Illuminate\Database\Seeder;

class BoardListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\BoardList::class, 8)->create();
    }
}
