<?php

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Users\User;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Settings\Setting;

$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'firstname' => $faker->firstName,
        'lastname'  => $faker->lastName,
        'email'     => $faker->email,
        'password'  => $password ?: bcrypt(Str::random(8)),
    ];
});

$factory->define(Page::class, function (Faker\Generator $faker) {
    return [
        'morph_key' => 'singles',
        'current_state'   => \Thinktomorrow\Chief\States\PageState::DRAFT,
        'title:nl'    => $faker->words(rand(2, 4), true),
    ];
});

$factory->define(MenuItem::class, function (Faker\Generator $faker) {
    return [
        'type'      => 'custom',
        'label:nl'  => 'nieuw label',
        'menu_type' => 'main'
    ];
});

$factory->define(Setting::class, function (Faker\Generator $faker) {
    return [
    ];
});
