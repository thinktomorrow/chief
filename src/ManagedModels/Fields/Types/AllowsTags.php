<?php

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsTags
{
    protected $tags = [];

    public function tagged($tag): bool
    {
        $tags = (array) $tag;

        return count(array_intersect($this->tags, $tags)) > 0;
    }

    public function untagged(): bool
    {
        return count($this->tags) < 1;
    }

    public function tag($tag)
    {
        $this->tags = array_merge($this->tags, (array)$tag);

        return $this;
    }

    public function untag($tag)
    {
        foreach ((array) $tag as $_tag) {
            if (false !== ($k = array_search($_tag, $this->tags))) {
                unset($this->tags[$k]);
            }
        }

        return $this;
    }
}
