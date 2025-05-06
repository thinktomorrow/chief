<?php

namespace Thinktomorrow\Chief\ManagedModels\States\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithMemoizedModel;
use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class EditState extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithMemoizedModel;
    use WithStateConfig;

    public string $parentComponentId;

    public string $stateKey;

    public ModelReference $modelReference;

    public ?string $transitionInConfirmationState = null;

    public ?string $errorMessage = null;

    public function mount(string $parentComponentId, string $stateKey, StatefulContract&ReferableModel $model)
    {
        $this->parentComponentId = $parentComponentId;
        $this->stateKey = $stateKey;
        $this->modelReference = $model->modelReference();

        $this->setMemoizedModel($model);
    }

    public function getListeners()
    {
        return [
            'open-edit-state-'.$this->parentComponentId => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['form', 'transitionInConfirmationState', 'errorMessage']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function getTitle(): ?string
    {
        return $this->getStateAdminConfig()?->getEditTitle($this->getModel());
    }

    public function getContent(): ?string
    {
        return $this->getStateAdminConfig()?->getEditContent($this->getModel());
    }

    /** @return Collection<TransitionDto> */
    public function getTransitions(): Collection
    {
        return collect($this->getStateMachine()->getAllowedTransitions())
            ->map(fn ($transitionKey) => TransitionDto::fromConfig($this->getModel(), $this->getStateAdminConfig(), $transitionKey));
    }

    public function transition(string $transitionKey): void
    {
        $transition = $this->findTransition($transitionKey);

        if ($transition->hasConfirmation && ! $this->transitionInConfirmationState) {
            $this->transitionInConfirmationState = $transitionKey;

            return;
        }

        $this->saveState($transitionKey);
    }

    public function getTransitionInConfirmationState(): ?TransitionDto
    {
        if (! $this->transitionInConfirmationState) {
            return null;
        }

        return $this->findTransition($this->transitionInConfirmationState);
    }

    public function closeConfirm(): void
    {
        $this->transitionInConfirmationState = null;
    }

    private function findTransition(string $transitionKey): TransitionDto
    {
        return $this->getTransitions()->first(fn ($transition) => $transition->key === $transitionKey);
    }

    public function saveState(string $transitionKey): void
    {
        $transition = $this->findTransition($transitionKey);

        try {

            // TODO: guard with permission: $this->guard('state-edit', $model);

            $resource = app(Registry::class)->findResourceByModel($this->getModel()::class);
            $formerState = $this->getModel()->getState($this->stateKey)->getValueAsString();

            app(UpdateState::class)->handle(
                $resource::resourceKey(),
                $this->modelReference,
                $this->stateKey,
                $transitionKey,
                $this->form,
                [], // files... TODO: implement file handling
            );
        } catch (StateException $e) {
            $this->errorMessage = 'Transition ['.$transitionKey.'] not applied';

            return;
        }

        if ($transition->redirectTo) {

            if ($transition->redirectNotification) {
                redirect()->to($transition->redirectTo)->with('messages.'.$transition->variant, $transition->redirectNotification);
            } else {
                redirect()->to($transition->redirectTo);
            }

            return;
        }

        $newState = $this->getFreshModel()->getState($this->stateKey)->getValueAsString();

        $this->dispatch('model-state-updated', ...[
            $this->modelReference,
            $this->stateKey,
            $formerState,
            $newState,
            $transitionKey,
        ]);

        $this->close();
    }

    public function render()
    {
        return view('chief-states::edit-state');
    }
}
