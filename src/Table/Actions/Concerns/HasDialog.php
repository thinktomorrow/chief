<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

use Closure;
use Thinktomorrow\Chief\Forms\Dialogs\Dialog;

trait HasDialog
{
    protected ?Closure $dialogResolver = null;

    public function dialog(Closure|Dialog $dialogResolver): static
    {
        $this->dialogResolver = $dialogResolver instanceof Dialog ? fn () => $dialogResolver : $dialogResolver;

        return $this;
    }

    public function hasDialog(): bool
    {
        return ! is_null($this->dialogResolver);
    }

    public function getDialogResolver(): Closure
    {
        return $this->dialogResolver;
    }
}
