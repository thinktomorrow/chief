<?php

namespace Thinktomorrow\Chief\TableNew\Columns\Concerns;

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
        if(($model = $this->getModel()) && $this->link instanceof Closure) {
            return call_user_func($this->link, $model);
        }

        return $this->link;
    }
}
