<?php

namespace Thinktomorrow\Chief\Forms\Tags;

interface HasTags
{
    public function getTagsAsString(): string;

    public function isTagged(string|array $tags): bool;

    public function isUntagged(): bool;

    public function tag(string|array $tags): static;

    public function untag(string|array $tags): static;
}
