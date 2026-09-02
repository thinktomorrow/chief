<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\UI\Livewire\DataTable;

trait HasLivewireComponent
{
    // Default Livewire table component
    private string $livewireComponentClass = DataTable::class;

    public function usesLivewireComponent(string $livewireComponentClass): static
    {
        $this->livewireComponentClass = $livewireComponentClass;

        return $this;
    }

    public function getLivewireComponentClass(): string
    {
        return $this->livewireComponentClass;
    }
}
