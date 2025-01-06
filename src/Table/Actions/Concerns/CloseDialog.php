<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

trait CloseDialog
{
    protected bool $closeDialog = true;

    public function closeDialog(bool $closeDialog = true): static
    {
        $this->closeDialog = $closeDialog;

        return $this;
    }

    public function keepDialogOpen(bool $keepDialogOpen = true): static
    {
        $this->closeDialog = ! $keepDialogOpen;

        return $this;
    }

    public function shouldCloseDialog(): bool
    {
        return $this->closeDialog;
    }
}
