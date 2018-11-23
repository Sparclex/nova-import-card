<?php

use Faker\Generator as Faker;
use Sparclex\NovaImportCard\Tests\Fixtures\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => $faker->name,
        'name' => $faker->name,
        'age' => $faker->randomNumber,
    ];
});
