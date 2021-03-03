<?php

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsTags
{
    protected array $tags = [];

    public function tagged($tag): bool
    {
        $tags = (array) $tag;

        return count(array_intersect($this->tags, $tags)) > 0;
    }

    public function untagged(): bool
    {
        return count($this->tags) < 1;
    }

    /**
     * @return AbstractField
     */
    public function tag($tag)
    {
        $this->tags = array_merge($this->tags, (array)$tag);

        return $this;
    }

    /**
     * @return AbstractField
     */
    public function untag($tag): self
    {
        foreach ((array) $tag as $_tag) {
            if (false !== ($k = array_search($_tag, $this->tags))) {
                unset($this->tags[$k]);
            }
        }

        return $this;
    }
}
