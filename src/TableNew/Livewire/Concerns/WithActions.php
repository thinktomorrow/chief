<?php

namespace Thinktomorrow\Chief\TableNew\Livewire\Concerns;

use Thinktomorrow\Chief\TableNew\Actions\Action;

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

    public function applyActionEffect(string $key)
    {
        $action = $this->getTable()->findAction($key);

        // Modal??

        // Compose Modal

        // Effect?

        if ($action->hasEffect()) {
            $action->getEffect()();
        }
    }
}
