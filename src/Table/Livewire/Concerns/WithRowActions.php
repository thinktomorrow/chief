<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Table\Actions\Action;

trait WithRowActions
{
    /**
     * @return Action[]
     */
    public function getVisibleRowActions($model): array
    {
        return array_filter($this->getTable()->getRowActions($model), fn (Action $action) => $action->isVisible());
    }

    public function getHiddenRowActions($model): array
    {
        return array_filter($this->getTable()->getRowActions($model), fn (Action $action) => ! $action->isVisible());
    }
}
