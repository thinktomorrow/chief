<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Table\Actions\Action;

class EditModelAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        return static::make('edit')
            ->link(function ($model) use ($resourceKey) {
                return '/admin/' . $resourceKey . '/' . $model->getKey() . '/edit';
            })
            ->iconEdit();
    }
}
