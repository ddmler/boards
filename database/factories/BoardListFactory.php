<?php

use Faker\Generator as Faker;

$factory->define(App\BoardList::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'board_id' => App\Board::all()->random()->id,
    ];
});
