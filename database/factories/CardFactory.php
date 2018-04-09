<?php

use Faker\Generator as Faker;

$factory->define(App\Card::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'board_list_id' => App\BoardList::all()->random()->id,
    ];
});
