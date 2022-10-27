<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasToggleDisplay
{
    protected bool $showAsToggle = false;

    public function showAsToggle($bool = true): static
    {
        $this->showAsToggle = $bool;

        return $this;
    }

    public function optedForToggleDisplay(): bool
    {
        return $this->showAsToggle;
    }
}
