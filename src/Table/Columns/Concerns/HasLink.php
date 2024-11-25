<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;

trait HasLink
{
    // targetBlank

    protected null|string|Closure $link = null;

    public function link(string|Closure $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function hasLink(): bool
    {
        return ! is_null($this->link);
    }

    public function getLink(): null|string
    {
        if ($this->link instanceof Closure) {
            return call_user_func($this->link, $this->getModel());
        }

        return $this->link;
    }
}
