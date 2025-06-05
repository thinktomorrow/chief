<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

trait HasReordering
{
    private bool $allowReordering = false;

    private bool $startWithReordering = false;

    private ?string $reorderingModelClass = null;

    public function allowReordering(bool $allowReordering = true): static
    {
        $this->allowReordering = $allowReordering;

        return $this;
    }

    public function isReorderingAllowed(): bool
    {
        return $this->allowReordering;
    }

    public function startWithReordering(bool $reorder = true): static
    {
        $this->startWithReordering = $reorder;

        return $this;
    }

    public function isStartingWithReordering(): bool
    {
        return $this->startWithReordering;
    }

    public function setReorderingModelClass(string $modelClass): static
    {
        $this->reorderingModelClass = $modelClass;

        return $this;
    }

    public function getReorderingModelClass(): ?string
    {
        return $this->reorderingModelClass;
    }
}
