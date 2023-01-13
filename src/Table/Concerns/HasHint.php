<?php

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasHint
{
    protected string|null|int $hint = null;

    public function getHint(): string|null|int
    {
        return $this->hint;
    }

    public function hint(string|null|int $hint): static
    {
        $this->hint = $hint;

        return $this;
    }
}
