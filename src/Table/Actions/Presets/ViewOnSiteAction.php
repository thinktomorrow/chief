<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Table\Actions\Action;

class ViewOnSiteAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        return static::make('view')
            ->label('Bekijk op site')
            ->link(function ($model) use ($resourceKey) {
                return $model->url();
            })
            ->prependIcon('<x-chief::icon.link-square />');
    }
}
