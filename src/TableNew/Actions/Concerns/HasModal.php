<?php

namespace Thinktomorrow\Chief\TableNew\Actions\Concerns;

use Thinktomorrow\Chief\Forms\Modals\Modal;

trait HasModal
{
    protected ?Modal $modal = null;

    public function modal(Modal $modal): static
    {
        $this->modal = $modal;

        return $this;
    }

    public function hasModal(): bool
    {
        return isset($this->modal);
    }

    public function getModal(): ?Modal
    {
        return $this->modal;
    }

}
