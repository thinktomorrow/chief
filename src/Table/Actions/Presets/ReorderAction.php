<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Table\Actions\Action;

class ReorderAction extends Action
{
    public static function makeDefault(): static
    {
        return static::make('reorder-start')
            ->label('Herschikken')
            ->description('Hiermee kunt u de de rangschikking op de site wijzigen.')
            ->prependIcon('<x-chief::icon.sorting />')
            ->effect(function ($formData, $data, $action, $component) {
                $component->startReordering();
            })
            ->when(fn ($component) => ! $component->isReordering);
    }
}
