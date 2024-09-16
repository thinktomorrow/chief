<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Dialogs\Concerns;

trait HasButton
{
    protected ?string $button = null;

    public function button(string $button): static
    {
        $this->button = $button;

        return $this;
    }

    public function getButton(): ?string
    {
        return $this->button;
    }
}
