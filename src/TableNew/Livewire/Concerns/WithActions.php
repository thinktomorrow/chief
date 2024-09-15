<?php

namespace Thinktomorrow\Chief\TableNew\Livewire\Concerns;

use Thinktomorrow\Chief\Forms\Dialogs\Livewire\TableActionDialogReference;
use Thinktomorrow\Chief\TableNew\Actions\Action;
use Thinktomorrow\Chief\TableNew\Actions\BulkAction;

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

    public function applyAction($actionKey)
    {
        $action = $this->getTable()->findAction($actionKey);

        if ($action->hasDialog()) {
            $this->showActionDialog($actionKey, $this->getActionDialogData($action));

            return;
        }

        if ($action->hasEffect()) {
            $action->getEffect()();
        }
    }

    private function getActionDialogData(Action $action): array
    {
        if ($action instanceof BulkAction) {
            return ['items' => $this->getBulkSelection()];
        }

        return [];
    }

    private function showActionDialog($actionKey, array $data = [])
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

    public function onActionDialogSaved($values)
    {
        $this->applyActionEffect(
            $values['dialogReference']['actionKey'],
            $values['form'],
            $values['data']
        );
    }

    private function applyActionEffect(string $key, array $formData, array $data = [])
    {
        $action = $this->getTable()->findAction($key);

        if ($action->hasEffect()) {
            // Pass model or model ids or nothing
            $action->getEffect()($formData, $data);
        }

        $this->dispatch('requestRefresh')->self();

        // Dispatch event for notification to user... or to refresh the table
    }

    public function openActionDialog($params)
    {
        // TODO:: modal or drawer or else ...
        $this->dispatch('open' . '-' . $this->getId(), $params)->to('chief-form::modal');
    }
}
