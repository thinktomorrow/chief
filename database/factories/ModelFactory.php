<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Chief\Models\Article;

$factory->define(\Chief\Users\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->email,
        'password' => $password ?: bcrypt('foobar'),
    ];
});

$factory->define(Article::class, function (Faker\Generator $faker) {
    return [
        'published' => false,
        'featured' => false,
        'publication' => null,
    ];
});

$factory->define(\Chief\Models\ArticleTranslation::class, function (Faker\Generator $faker) {
    $article = factory(Article::class)->create();
    return [
        'locale'        =>  'nl',
        'title'         =>  'test',
        'content'       => $faker->title,
        'article_id'    => $article->id,
    ];
});


