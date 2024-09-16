<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Table\Actions\Action;

trait WithBulkActions
{
    /**
     * @return Action[]
     */
    public function getVisibleBulkActions(): array
    {
        return array_filter($this->getTable()->getBulkActions(), fn (Action $action) => $action->isVisible());
    }

    public function getHiddenBulkActions(): array
    {
        return array_filter($this->getTable()->getBulkActions(), fn (Action $action) => ! $action->isVisible());
    }

    public function hasAnyBulkActions(): bool
    {
        return count($this->getTable()->getBulkActions()) > 0;
    }

    public function applyBulkActionEffect(string $key, $models)
    {
        $action = $this->getTable()->findBulkAction($key);

        // Modal??

        // Compose Modal

        // Effect?

        if ($action->hasEffect()) {
            $action->getEffect()($models);
        }
    }
}
