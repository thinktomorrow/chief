<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Context;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithMemoizedModel;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\Items;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

class Contexts extends Items
{
    use WithMemoizedModel;

    public ModelReference $modelReference;

    // Memoized collection
    private ?Collection $contexts = null;

    public function mount(ReferableModel&ContextOwner $model, ?string $activeContextId = null)
    {
        $this->modelReference = $model->modelReference();

        $locales = $model instanceof HasAllowedSites ? $model->getAllowedSites() : ChiefSites::locales();

        $this->setMemoizedModel($model);

        $this->mountItems($locales, $activeContextId);
    }

    /** @return Collection<ContextDto> */
    public function getItems(): Collection
    {
        if ($this->contexts) {
            return $this->contexts;
        }

        return $this->contexts = app(ComposeLivewireDto::class)
            ->getContextsByOwner($this->getModel());
    }

    public function addItem(): void
    {
        $this->dispatch('open-add-item')->to('chief-wire::add-context');
    }

    public function editItem(string $itemId): void
    {
        $this->dispatch('open-edit-item', [
            'itemId' => $itemId,
        ])->to('chief-wire::edit-context');
    }

    public function render()
    {
        return view('chief-fragments::livewire.contexts');
    }
}
