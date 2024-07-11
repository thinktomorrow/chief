<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Thinktomorrow\Chief\TableNew\Livewire\TableComponent;

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
