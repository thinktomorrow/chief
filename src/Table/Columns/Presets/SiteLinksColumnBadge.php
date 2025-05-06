<?php

namespace Thinktomorrow\Chief\Table\Columns\Presets;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Table\Columns\ColumnBadge;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class SiteLinksColumnBadge extends ColumnBadge
{
    public static function makeDefault(): static
    {
        return static::make('sites')->label('Sites')->items(function (Visitable&HasAllowedSites $model) {

            $urls = $model->urls;
            $items = [];

            foreach (ChiefSites::verifiedLocales($model->getAllowedSites()) as $site) {

                $url = $urls->firstWhere('site', $site);

                $items[] = (object) [
                    'locale' => $site,
                    'name' => ChiefSites::name($site),
                    'shortName' => ChiefSites::shortName($site),
                    'status' => ($url && $model->isVisitable()) ? LinkStatus::from($url->status) : LinkStatus::offline,
                    'url' => $model->url($site),
                ];
            }

            return $items;
        })->eachItem(function ($object, $columnItem) {
            $columnItem->value($object->shortName)
                ->variant($object->status === LinkStatus::online ? 'green' : 'grey');
        });
    }
}
