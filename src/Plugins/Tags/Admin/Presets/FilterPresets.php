<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Admin\Presets;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\ManagedModels\Filters\Filter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\SelectFilter;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagReadRepository;

class FilterPresets
{
    public static function tags(): Filter
    {
        return SelectFilter::make('tags', function ($query, $value) {
            $tagIds = (array) $value;

            $query->whereHas('tags', function (Builder $q) use ($tagIds) {
                $q->whereIn('id', $tagIds);
            });
        })->label('Tag')
        ->options(app(TagReadRepository::class)->getAllForSelect())
        ->default('')
        ->view('chief-tags::filters.tags');
    }
}
