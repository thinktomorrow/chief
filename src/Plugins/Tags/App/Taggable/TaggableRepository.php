<?php

namespace Thinktomorrow\Chief\Plugins\Tags\App\Taggable;

interface TaggableRepository
{
    public function attachTags(array $taggableIds, array $tagIds): void;

    public function detachTags(array $taggableIds, array $tagIds): void;

    public function syncTags(Taggable $taggable, array $tagIds): void;
}
