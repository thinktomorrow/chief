<?php

namespace Thinktomorrow\Chief\ManagedModels\States\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\BelongsToSites;

class State extends Component
{
    use WIthStateConfig;

    public string $stateKey;

    public string $modelReference;

    public function mount(string $stateKey, Visitable&BelongsToSites&ReferableModel&ContextOwner $model)
    {
        $this->stateKey = $stateKey;
        $this->modelReference = $model->modelReference()->get();
    }

    public function getListeners()
    {
        return [
            'model-state-updated' => 'onModelStateUpdated',
        ];
    }

    public function edit(): void
    {
        $this->dispatch('open-edit-state-'.$this->getId())->to('chief-wire::edit-state');
    }

    public function onModelStateUpdated(): void
    {
        // The links are automatically updated in the view
        // because the component methods are called again.
    }

    public function getStateLabel(): string
    {
        return $this->getStateConfig()->getStateLabel($this->getModel());
    }

    public function getStateLabelString(): string
    {
        return $this->getStateConfig()->getStateLabelString($this->getModel());
    }

    public function isAllowedToEdit(): bool
    {
        return count($this->getStateMachine()->getAllowedTransitions()) > 0;
    }

    public function render()
    {
        return view('chief-states::state');
    }
}
