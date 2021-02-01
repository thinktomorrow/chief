<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Database\Factories;

use Thinktomorrow\Chief\Admin\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Thinktomorrow\Chief\ManagedModels\States\PageState;

final class PageFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'morph_key'     => 'singles',
            'current_state' => PageState::DRAFT,
            'title:nl'      => $this->faker->words(rand(2, 4), true),
        ];
    }
}
