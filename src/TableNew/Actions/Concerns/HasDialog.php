<?php

namespace Thinktomorrow\Chief\TableNew\Actions\Concerns;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;

trait HasDialog
{
    protected ?Dialog $dialog = null;

    public function dialog(Dialog $dialog): static
    {
        $this->dialog = $dialog;

        return $this;
    }

    public function hasDialog(): bool
    {
        return isset($this->dialog);
    }

    public function getDialog(): ?Dialog
    {
        return $this->dialog;
    }

}
