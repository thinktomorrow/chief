<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasSave
{
    protected ?\Closure $save = null;

    public function save(\Closure $save): static
    {
        $this->save = $save;

        return $this;
    }

    public function hasSave(): bool
    {
        return ! is_null($this->save);
    }

    public function getSave(): ?\Closure
    {
        if (! $this->hasSave()) {
            return null;
        }

        return $this->save;
    }
}
