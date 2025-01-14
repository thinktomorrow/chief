<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\Livewire\TableComponent;

trait HasLivewireComponent
{
    // Default Livewire table component
    private string $livewireComponentClass = TableComponent::class;

    public function usesLivewireTable(string $livewireComponentClass): static
    {
        $this->livewireComponentClass = $livewireComponentClass;

        return $this;
    }

    public function getLivewireComponentClass(): string
    {
        return $this->livewireComponentClass;
    }
}
