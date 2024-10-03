<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository;
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
