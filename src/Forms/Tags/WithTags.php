<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tags;

trait WithTags
{
    protected array $tags = [];

    public function getTagsAsString(): string
    {
        return implode(',', $this->tags);
    }

    public function isTagged(string|array $tags): bool
    {
        $tags = (array) $tags;

        return count(array_intersect($this->tags, $tags)) > 0;
    }

    public function isUntagged(): bool
    {
        return count($this->tags) < 1;
    }

    public function tag(string|array $tags): static
    {
        $this->tags = array_merge($this->tags, (array) $tags);

        return $this;
    }

    public function untag(string|array $tags): static
    {
        foreach ((array) $tags as $tag) {
            if (false !== ($k = array_search($tag, $this->tags))) {
                unset($this->tags[$k]);
            }
        }

        return $this;
    }
}
