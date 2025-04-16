<?php

namespace Thinktomorrow\Chief\Table\Filters\Presets;

use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Table\Filters\ButtonGroupFilter;

class SiteFilter extends ButtonGroupFilter
{
    public static function makeDefault(): self
    {
        return static::make('allowed_sites')
            ->label('Site')
            ->query(function ($query, $value) {
                return $query->byAllowedSiteOrNone($value);
            })
            ->options([
                '' => 'Alle',
                ...ChiefSites::all()->toCollection()->mapWithKeys(
                    fn (ChiefSite $site) => [$site->locale => $site->name]
                )->toArray(),
            ])->value('');
    }
}
