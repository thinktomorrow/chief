<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Table\Actions\Action;

class EditModelAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        $resource = app(Registry::class)->resource($resourceKey);
        $manager = app(Registry::class)->manager($resourceKey);

        return static::make('edit')
            ->label("Bewerk {$resource->getLabel()}");
        //            ->icon('<x-chief::icon.quill-write />')
        //            ->rowLink(function ($model) use ($manager) {
        //                return $manager->route('edit', $model);
        //            });
    }
}
