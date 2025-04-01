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

        $this->resetActiveContext($activeContextId);
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
            $this->modelReference.'-context-deleted' => 'onContextDeleted',

        ];
    }

    public function showContext(string $contextId): void
    {
        $this->activeContextId = $contextId;
    }

    public function addContext(): void
    {
        $this->dispatch('open-add-context')->to('chief-wire::add-context');
    }

    public function editContext(string $contextId): void
    {
        $this->dispatch('open-edit-context', [
            'contextId' => $contextId,
        ])->to('chief-wire::edit-context');
    }

    public function onContextsUpdated(string $contextId): void
    {
        // The contexts are automatically updated in the view
        $this->activeContextId = $contextId;
    }

    public function onContextDeleted(): void
    {
        // If the active context is deleted, reset the active context
        $this->resetActiveContext();
    }

    private function resetActiveContext(?string $activeContextId = null): void
    {
        $this->activeContextId = (is_null($activeContextId))
            ? $this->getContexts()->first()?->id
            : $activeContextId;
    }

    public function render()
    {
        return view('chief-fragments::livewire.contexts');
    }
}
