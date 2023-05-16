<?php

namespace Thinktomorrow\Chief\Plugins\Tags\App\Presets;

use Thinktomorrow\Chief\ManagedModels\Filters\Filter;

class FilterPresets
{
    public static function tags(): Filter
    {
        return (new TagsFilter)
            ->filterByUsedTags()
            ->label('Tags');
    }
}
