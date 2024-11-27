<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Table\Actions\Action;

trait WithBulkActions
{
    /** @return Action[] */
    public function getPrimaryBulkActions(): array
    {
        return array_filter($this->getTable()->getBulkActions(), fn (Action $action) => $action->isPrimary());
    }

    /** @return Action[] */
    public function getSecondaryBulkActions(): array
    {
        return array_filter($this->getTable()->getBulkActions(), fn (Action $action) => $action->isSecondary());
    }

    /** @return Action[] */
    public function getTertiaryBulkActions(): array
    {
        return array_filter($this->getTable()->getBulkActions(), fn (Action $action) => $action->isTertiary());
    }

    public function hasAnyBulkActions(): bool
    {
        return count($this->getResults()) > 0 && count($this->getTable()->getBulkActions()) > 0;
    }
}
