<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Dialogs\Concerns;

trait HasDialogSize
{
    protected ?string $dialogSize = null;

    public function dialogSize(string $dialogSize): static
    {
        $this->dialogSize = $dialogSize;

        return $this;
    }

    public function getDialogSize(): ?string
    {
        return $this->dialogSize;
    }
}
