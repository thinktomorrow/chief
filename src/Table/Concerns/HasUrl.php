<?php

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasUrl
{
    protected string $url;

    protected string $target = '_self';

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Usually one of: _blank, _self, _parent, _top
     */
    public function target(string $target): static
    {
        $this->target = $target;

        return $this;
    }
}
