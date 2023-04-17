<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories;

use Thinktomorrow\Chief\Plugins\Tags\Application\Taggable\Taggable;

class EloquentTaggableRepository implements \Thinktomorrow\Chief\Plugins\Tags\Application\Taggable\TaggableRepository
{
    public function attachTags(Taggable $taggable, array $tagIds): void
    {
        // TODO: Implement attachTags() method.
    }

    public function detachTags(Taggable $taggable, array $tagIds): void
    {
        // TODO: Implement detachTags() method.
    }

    public function syncTags(Taggable $taggable, array $tagIds): void
    {
        $taggable->tags()->sync($tagIds);
    }
}
