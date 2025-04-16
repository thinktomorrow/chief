<?php

namespace Thinktomorrow\Chief\Table\Columns\Presets;

use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Table\Columns\ColumnBadge;

class SitesColumnBadge extends ColumnBadge
{
    public static function makeDefault(): static
    {
        return static::make('sites')->label('Site')->items(function (HasAllowedSites $model) {

            $items = [];

            foreach ($model->getAllowedSites() as $site) {
                $items[] = (object) [
                    'locale' => $site,
                    'name' => ChiefSites::name($site),
                    'shortName' => ChiefSites::shortName($site),
                ];
            }

            return $items;
        })->eachItem(function ($siteObject, $columnItem) {
            $columnItem->value($siteObject->shortName)
                ->variant('grey');
        });
    }
}
