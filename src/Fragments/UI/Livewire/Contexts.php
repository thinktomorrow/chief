<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class Contexts extends Component
{
    public string $modelReference;

    public ?string $activeContextId = null;

    public function mount(ReferableModel&ContextOwner $model, ?string $activeContextId = null)
    {
        $this->modelReference = $model->modelReference()->get();

        $this->activeContextId = (is_null($activeContextId))
            ? $this->getContexts()->first()?->id
            : $activeContextId;
    }

    /** @return Collection<ContextDto> */
    public function getContexts(): Collection
    {
        return app(ComposeLivewireDto::class)
            ->getContextsByOwner(ModelReference::fromString($this->modelReference));
    }

    public function getListeners()
    {
        return [
            $this->modelReference.'-contexts-updated' => 'onContextsUpdated',
        ];
    }

    public function showContext(string $contextId): void
    {
        $this->activeContextId = $contextId;
    }

    public function editContexts(): void
    {
        $this->dispatch('open-edit-contexts')->to('chief-wire::edit-contexts');
    }

    public function onContextsUpdated(): void
    {
        // The contexts are automatically updated in the view
    }

    public function render()
    {
        return view('chief-fragments::livewire.contexts');
    }
}
