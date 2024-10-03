<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Table\Actions\Action;

class VisitArchiveAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        $resource = app(Registry::class)->resource($resourceKey);
        $manager = app(Registry::class)->manager($resourceKey);

        return static::make('archive-index')
            ->label("Bekijk archief")
            ->link($manager->route('archive_index'))
            ->hidden();
    }
}
