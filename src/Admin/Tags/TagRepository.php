<?php

namespace Thinktomorrow\Chief\Admin\Tags;

interface TagRepository
{
    public function attachTags(Taggable $taggable, array $tagIds): void;

    public function detachTags(Taggable $taggable, array $tagIds): void;

    public function syncTags(Taggable $taggable, array $tagIds): void;
}
