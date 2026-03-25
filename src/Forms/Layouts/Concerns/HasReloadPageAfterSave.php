<?php

namespace Thinktomorrow\Chief\Forms\Layouts\Concerns;

trait HasReloadPageAfterSave
{
    protected bool $reloadPageAfterSave = false;

    public function reloadPageAfterSave(bool $reloadPageAfterSave = true): static
    {
        $this->reloadPageAfterSave = $reloadPageAfterSave;

        return $this;
    }

    public function setReloadPageAfterSave(bool $reloadPageAfterSave): static
    {
        $this->reloadPageAfterSave = $reloadPageAfterSave;

        return $this;
    }

    public function shouldReloadPageAfterSave(): bool
    {
        return $this->reloadPageAfterSave;
    }
}
