<?php

namespace Thinktomorrow\Chief\Forms\Fields\Repeat\Concerns;

use Thinktomorrow\Chief\Forms\Fields\Repeat\Livewire\RepeatComponent;

trait HasLivewireComponent
{
    // Default Livewire table component
    private string $livewireComponentClass = RepeatComponent::class;

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
