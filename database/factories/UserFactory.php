<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Database\Factories;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Admin\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname'  => $this->faker->lastName,
            'email'     => $this->faker->email,
            'password'  => bcrypt(Str::random(8)),
        ];
    }
}
