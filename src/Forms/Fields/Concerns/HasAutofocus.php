<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasAutofocus
{
    protected bool $autofocus = false;

    public function autofocus(bool $autofocus = true): static
    {
        $this->autofocus = $autofocus;

        return $this;
    }

    public function hasAutofocus(): bool
    {
        return $this->autofocus;
    }
}
