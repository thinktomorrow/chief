<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Application\Taggable;

interface TaggableRepository
{
    public function attachTags(Taggable $taggable, array $tagIds): void;

    public function detachTags(Taggable $taggable, array $tagIds): void;

    public function syncTags(Taggable $taggable, array $tagIds): void;
}
