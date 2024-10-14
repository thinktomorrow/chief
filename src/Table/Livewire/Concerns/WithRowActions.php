<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Table\Actions\Action;

trait WithRowActions
{
    /** @return Action[] */
    public function getPrimaryRowActions($model): array
    {
        return array_filter($this->getTable()->getRowActions($model), fn (Action $action) => $action->isPrimary());
    }

    /** @return Action[] */
    public function getSecondaryRowActions($model): array
    {
        return array_filter($this->getTable()->getRowActions($model), fn (Action $action) => $action->isSecondary());
    }

    /** @return Action[] */
    public function getTertiaryRowActions($model): array
    {
        return array_filter($this->getTable()->getRowActions($model), fn (Action $action) => $action->isTertiary());
    }
}
