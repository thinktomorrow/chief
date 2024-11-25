<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;

trait HasLink
{
    protected null|string|Closure $link = null;
    protected bool $openInNewTab = false;

    public function link(string|Closure $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getLink(): null|string
    {
        if (($model = $this->getModel()) && $this->link instanceof Closure) {
            return call_user_func($this->link, $model);
        }

        return $this->link;
    }

    public function hasLink(): bool
    {
        return ! is_null($this->link);
    }

    public function openInNewTab(bool $openInNewTab = true): static
    {
        $this->openInNewTab = $openInNewTab;

        return $this;
    }

    public function shouldOpenInNewTab(): bool
    {
        return $this->openInNewTab;
    }
}
