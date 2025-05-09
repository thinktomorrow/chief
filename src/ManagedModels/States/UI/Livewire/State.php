<?php

namespace Thinktomorrow\Chief\ManagedModels\States\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithMemoizedModel;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class State extends Component
{
    use WithMemoizedModel;
    use WithStateConfig;

    public string $stateKey;

    public ModelReference $modelReference;

    public function mount(string $stateKey, StatefulContract&ReferableModel $model)
    {
        $this->stateKey = $stateKey;
        $this->modelReference = $model->modelReference();

        $this->setMemoizedModel($model);
    }

    public function getListeners()
    {
        return [
            'model-state-updated' => 'onModelStateUpdated',
            'links-updated' => 'onModelStateUpdated',
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

    public function getStateVariant(): string
    {
        return $this->getStateConfig()->getStateVariant($this->getModel());
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
