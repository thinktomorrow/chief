<?php

namespace Thinktomorrow\Chief\Plugins\Tags\App\Taggable;

interface TaggableRepository
{
    public function attachTags(string $taggableType, array $taggableIds, array $tagIds): void;

    public function detachTags(string $taggableType, array $taggableIds, array $tagIds): void;

    public function syncTags(string $taggableType, array $taggableIds, array $tagIds): void;
}
