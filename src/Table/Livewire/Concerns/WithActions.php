<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Forms\Dialogs\Livewire\TableActionDialogReference;
use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Actions\BulkAction;
use Thinktomorrow\Chief\Table\Actions\RowAction;

trait WithActions
{
    /**
     * @return Action[]
     */
    public function getVisibleActions(): array
    {
        return array_filter($this->getTable()->getActions(), fn (Action $action) => $action->isVisible());
    }

    public function getHiddenActions(): array
    {
        return array_filter($this->getTable()->getActions(), fn (Action $action) => ! $action->isVisible());
    }

    public function applyAction($actionKey, array $payload = []): void
    {
        $action = $this->getTable()->findAction($actionKey);

        if ($action->hasDialog()) {
            $this->showActionDialog($actionKey, $this->getActionPayload($action, $payload));

            return;
        }

        if ($action->hasEffect()) {
            $this->applyActionEffect(
                $actionKey,
                [],
                $this->getActionPayload($action, $payload)
            );
        }
    }

    public function applyRowAction($actionKey, string $modelReference): void
    {
        $this->applyAction($actionKey, ['modelReference' => $modelReference]);
    }

    private function getActionPayload(Action $action, array $payload = []): array
    {
        if ($action instanceof BulkAction) {
            return ['items' => $this->getBulkSelection()];
        }

        if ($action instanceof RowAction) {
            return ['item' => $payload['modelReference']];
        }

        return [];
    }

    private function applyActionEffect(string $key, array $formData, array $data = []): void
    {
        $action = $this->getTable()->findAction($key);

        if ($action->hasEffect()) {

            // Perform effect
            $effectResult = $action->getEffect()($formData, $data);

            // Redirect after success
            if ($effectResult && $action->hasRedirectOnSuccess()) {
                redirect()->to($action->getRedirectOnSuccess()($formData, $data));
                return;
            }

            // Effect notification on success or failure
            if ($effectResult && $action->hasNotificationOnSuccess()) {
                $this->showNotification($action->getNotificationOnSuccess()($effectResult, $formData, $data), 'success');
            } elseif (! $effectResult && $action->hasNotificationOnFailure()) {
                $this->showNotification($action->getNotificationOnFailure()($effectResult, $formData, $data), 'error');
            }
        }

        $this->bulkDeselectAll();

        if ($action->shouldRefreshTable()) {
            $this->dispatch('requestRefresh')->self();
        }
    }

    public function openActionDialog($params): void
    {
        $this->dispatch('open' . '-' . $this->getId(), $params)->to('chief-form::dialog');
    }

    private function showActionDialog($actionKey, array $data = []): void
    {
        $action = $this->getTable()->findAction($actionKey);

        $dialogReference = new TableActionDialogReference(
            $this->getTable()->getTableReference(),
            $action->getKey(),
            $action->getDialog()->getId()
        );

        $this->openActionDialog([
            'dialogReference' => $dialogReference->toLivewire(),
            'data' => $data,
        ]);
    }

    public function onActionDialogSaved($values): void
    {
        $this->applyActionEffect(
            $values['dialogReference']['actionKey'],
            $values['form'],
            $values['data']
        );
    }
}
