<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Table\Actions\Action;

trait WithBulkActions
{
    /** @return Action[] */
    public function getPrimaryBulkActions(): array
    {
        return $this->getBulkActionsByVariant('primary');
    }

    /** @return Action[] */
    public function getSecondaryBulkActions(): array
    {
        return $this->getBulkActionsByVariant('secondary');
    }

    /** @return Action[] */
    public function getTertiaryBulkActions(): array
    {
        return $this->getBulkActionsByVariant('tertiary');
    }

    private function getBulkActionsByVariant(string $variant): array
    {
        $variantActions = array_filter($this->getTable()->getBulkActions(), fn (Action $action) => $action->{'is'.ucfirst($variant)}());

        return array_filter($variantActions, fn (Action $action) => ! $action->hasWhen() || $action->getWhen()($this));
    }

    public function hasAnyBulkActions(): bool
    {
        return count($this->getTable()->getBulkActions()) > 0;
    }
}
