<?php

use Chief\Articles\Article;
use Chief\Users\User;

$factory->define(User::class, function (Faker\Generator $faker) {

    static $password;

    return [
        'firstname' => $faker->firstName,
        'lastname'  => $faker->lastName,
        'email'     => $faker->email,
        'password'  => $password ?: bcrypt(str_random(8)),
    ];
});

$factory->define(Article::class, function (Faker\Generator $faker) {
    return [
        'published'   => $faker->boolean(),
        'featured'    => $faker->boolean(),
        'publication' => null,
        'title:nl'    => $faker->words(rand(2, 4), true),
        'title:fr'    => $faker->words(rand(2, 4), true),
        'short:nl'    => $faker->words(rand(10, 14), true),
        'short:fr'    => $faker->words(rand(10, 14), true),
        'slug:nl'     => $faker->unique()->slug,
        'slug:fr'     => $faker->unique()->slug,
        'content:nl'  => $faker->paragraph(rand(6, 12)),
        'content:fr'  => $faker->paragraph(rand(4, 10)),
    ];
});


