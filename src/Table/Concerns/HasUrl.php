<?php

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasUrl
{
    protected string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
