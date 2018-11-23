<?php

use Faker\Generator as Faker;
use Sparclex\NovaImportCard\Tests\Fixtures\User;
use Sparclex\NovaImportCard\Tests\Fixtures\Address;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'street' => $faker->word,
    ];
});
