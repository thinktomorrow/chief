<?php

namespace Thinktomorrow\Chief\Plugins\Tags\App\Presets;

use Thinktomorrow\Chief\ManagedModels\Filters\Filter;

class FilterPresets
{
    public static function tags(): Filter
    {
        return (new TagsFilter)->label('Tags');

        //        return SelectFilter::make('tags', function ($query, $value) {
        //            $tagIds = (array) $value;
        //
        //            $query->whereHas('tags', function (Builder $q) use ($tagIds) {
        //                $q->whereIn('id', $tagIds);
        //            });
        //        })->label('Tag')
        //        ->options(app(TagReadRepository::class)->getAllForSelect())
        //        ->default('')
        //        ->view('chief-tags::filters.tags');
    }

//    public static function tags(): Filter
//    {
//        return SelectFilter::make('tags', function ($query, $value) {
//            $tagIds = (array) $value;
//
//            $query->whereHas('tags', function (Builder $q) use ($tagIds) {
//                $q->whereIn('id', $tagIds);
//            });
//        })->label('Tag')
//            ->options(app(TagReadRepository::class)->getAllForSelect())
//            ->default('')
//            ->view('chief-tags::filters.tags');
//    }
}
