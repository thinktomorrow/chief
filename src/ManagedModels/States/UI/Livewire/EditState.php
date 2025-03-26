<?php

namespace Thinktomorrow\Chief\ManagedModels\States\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\Managers\Register\Registry;

class EditState extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithStateConfig;

    public string $parentComponentId;

    public string $stateKey;

    public string $modelReference;

    public ?string $transitionInConfirmationState = null;

    public ?string $errorMessage = null;

    public function mount(string $parentComponentId, string $stateKey, string $modelReference)
    {
        $this->parentComponentId = $parentComponentId;
        $this->stateKey = $stateKey;
        $this->modelReference = $modelReference;
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
                $this->getModel()->modelReference(),
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

        // In case that the state makes the model inaccessible (such as a deletion)
        // we'll want to redirect to a different page.
        //        $stateConfig = $model->getStateConfig($key);
        //        $redirect = $stateConfig->getRedirectAfterTransition($transitionKey, $model);
        //
        //        // A custom redirect is present so we'll return to the redirect.
        //        if ($redirect && ! $request->expectsJson()) {
        //            if ($notification = $stateConfig->getResponseNotification($transitionKey)) {
        //                return redirect()->to($redirect)->with(
        //                    'messages.'.($stateConfig->getTransitionType($transitionKey) ?: 'info'),
        //                    $notification
        //                );
        //            }
        //
        //            return redirect()->to($redirect);
        //        }
        //
        //        // Default when we don't have a custom redirect and no json response
        //        // is expected, we'll go back to the current page
        //        if (! $request->expectsJson()) {
        //            return redirect()->back();
        //        }
        //
        //        return response()->json([
        //            'message' => 'Transition ['.$transitionKey.'] applied',
        //            'redirect_to' => $redirect,
        //        ]);
    }

    public function render()
    {
        return view('chief-states::edit-state');
    }
}
