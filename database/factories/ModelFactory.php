<?php

use Thinktomorrow\Chief\Users\User;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Menu\MenuItem;

$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'firstname' => $faker->firstName,
        'lastname'  => $faker->lastName,
        'email'     => $faker->email,
        'password'  => $password ?: bcrypt(str_random(8)),
    ];
});

$factory->define(Page::class, function (Faker\Generator $faker) {
    return [
        'published'   => $faker->boolean(),
        'featured'    => $faker->boolean(),
        'publication' => null,
        'title:nl'    => $faker->words(rand(2, 4), true),
        'title:en'    => $faker->words(rand(2, 4), true),
        'short:nl'    => $faker->words(rand(10, 14), true),
        'short:en'    => $faker->words(rand(10, 14), true),
        'slug:nl'     => $faker->unique()->slug,
        'slug:en'     => $faker->unique()->slug,
        'content:nl'  => $faker->paragraph(rand(6, 12)),
        'content:en'  => $faker->paragraph(rand(4, 10)),
    ];
});

$factory->define(MenuItem::class, function(Faker\Generator $faker) {
    return [
        'type'  => 'custom',
        'label:nl' => 'nieuw label',
        'url:nl'   => 'https://thinktomorrow.be',
    ];
});
