<?php

use App\Note;
use App\User;
use Faker\Generator as Faker;

$factory->define(Note::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->Create()->id;
        },
        'title' => $faker->word,
        'body' => $faker->paragraph,
        'color' => 'grey'
    ];
});
