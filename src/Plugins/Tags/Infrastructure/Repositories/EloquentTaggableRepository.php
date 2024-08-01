<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories;

use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;

class EloquentTaggableRepository implements \Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository
{
    public function attachTags(array $taggableIds, array $tagIds): void
    {
        // Create array for a combination of taggable and tag ids
        $matrix = collect($taggableIds)->crossJoin($tagIds);
dd($taggableIds, $tagIds, $matrix->all());

        

        // Attach tag to pivot if it doesn't exist yet
        foreach ($taggableIds as $taggableId) {
            foreach ($tagIds as $tagId) {
                $taggable = Taggable::find($taggableId);
                $taggable->tags()->attach($tagId);
            }
        }
    }

    public function detachTags(array $taggableIds, array $tagIds): void
    {
        // TODO: Implement detachTags() method.
    }

    public function syncTags(Taggable $taggable, array $tagIds): void
    {
        $taggable->tags()->sync($tagIds);
    }
}
