<?php

namespace Thinktomorrow\Chief\Table\Columns\Presets;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Table\Columns\ColumnBadge;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class LinksColumnBadge extends ColumnBadge
{
    public static function makeDefault(): static
    {
        return static::make('links')->label('Links')->items(function (Visitable $model) {

            $urls = $model->urls;
            $items = [];

            foreach ($urls as $url) {
                $items[] = (object) [
                    'locale' => $url->site,
                    'status' => $model->isVisitable() ? LinkStatus::from($url->status) : LinkStatus::offline,
                    'url' => $model->url($url->site),
                ];
            }

            return $items;
        })->eachItem(function ($urlObject, $columnItem) {
            $columnItem->value($urlObject->locale)
                ->variant($urlObject->status === LinkStatus::online ? 'green' : 'grey');
        });
    }
}
