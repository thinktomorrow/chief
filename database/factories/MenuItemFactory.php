<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Database\Factories;

use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

final class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition()
    {
        return [
            'type'      => 'custom',
            'label:nl'  => 'nieuw label',
            'menu_type' => 'main'
        ];
    }
}
