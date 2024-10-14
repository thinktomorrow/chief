<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Table\Actions\Action;

class ReorderAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        $manager = app(Registry::class)->manager($resourceKey);

        return static::make('reorder')
            ->label("Herschikken")
            ->prependIcon('<x-chief::icon.sorting />')
            ->link($manager->route('index-for-sorting'))
            ->hidden();
    }
}
