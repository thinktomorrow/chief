<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasTaggable
{
    protected bool $allowTaggable = false;

    public function taggable(bool $flag = true): static
    {
        $this->allowTaggable = $flag;

        return $this;
    }

    public function allowTaggable(): bool
    {
        return $this->allowTaggable;
    }
}
