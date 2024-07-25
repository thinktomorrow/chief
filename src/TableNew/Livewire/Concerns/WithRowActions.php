<?php

namespace Thinktomorrow\Chief\TableNew\Livewire\Concerns;

use Thinktomorrow\Chief\TableNew\Actions\Action;

trait WithRowActions
{
    /**
     * @return Action[]
     */
    public function getVisibleRowActions(): array
    {
        return array_filter($this->getTable()->getRowActions(), fn (Action $action) => $action->isVisible());
    }

    public function getHiddenRowActions(): array
    {
        return array_filter($this->getTable()->getRowActions(), fn (Action $action) => ! $action->isVisible());
    }

    public function applyRowActionEffect(string $key, $model)
    {
        $action = $this->getTable()->findRowAction($key);

        // Modal??

        // Compose Modal

        // Effect?

        if ($action->hasEffect()) {
            $action->getEffect()($model);
        }
    }
}
