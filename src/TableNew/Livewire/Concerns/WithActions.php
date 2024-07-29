<?php

namespace Thinktomorrow\Chief\TableNew\Livewire\Concerns;

use Thinktomorrow\Chief\Forms\Modals\Livewire\TableActionModalReference;
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

        if($action->hasModal()) {
            $this->showActionModal($actionKey, $this->getActionModalData($action));
            return;
        }

        if ($action->hasEffect()) {
            $action->getEffect()();
        }
    }

    private function getActionModalData(Action $action): array
    {
        if($action instanceof BulkAction) {
            return ['items' => $this->getBulkSelection()];
        }

        return [];
    }

    private function showActionModal($actionKey, array $data = [])
    {
        $action = $this->getTable()->findAction($actionKey);

        $modalReference = new TableActionModalReference(
            $this->getTable()->getTableReference(),
            $action->getKey(),
            $action->getModal()->getId()
        );

        $this->openActionModal([
            'modalReference' => $modalReference->toLivewire(),
            'data' => $data,
        ]);
    }

    public function onActionModalSaved($values)
    {
        $this->applyActionEffect(
            $values['modalReference']['actionKey'],
            $values['form'],
            $values['data']
        );

        $action = $this->getTable()->findAction($values['modalReference']['actionKey']);
    }

    private function applyActionEffect(string $key, array $formData, array $data = [])
    {
        $action = $this->getTable()->findAction($key);

        // Compose Modal

        // Effect?

        if ($action->hasEffect()) {
            // Pass model or model ids or nothing
            $action->getEffect()($formData, $data);
        }
    }

    public function openActionModal($params)
    {
        $this->dispatch('open' . '-' . $this->getId(), $params)->to('chief-form::modal');
    }
}
