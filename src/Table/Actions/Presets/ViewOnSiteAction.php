<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Table\Actions\Action;

class ViewOnSiteAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        return static::make('view')
            ->link(function ($model) {
                return $model->url();
            })
            ->icon('<x-chief::icon.link-square />');
    }
}
