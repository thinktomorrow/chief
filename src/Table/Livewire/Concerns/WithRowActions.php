<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Table\Actions\Action;

trait WithRowActions
{
    /** @return Action[] */
    public function getPrimaryRowActions($model): array
    {
        return $this->getRowActionsByVariant('primary', $model);
    }

    /** @return Action[] */
    public function getSecondaryRowActions($model): array
    {
        return $this->getRowActionsByVariant('secondary', $model);
    }

    /** @return Action[] */
    public function getTertiaryRowActions($model): array
    {
        return $this->getRowActionsByVariant('tertiary', $model);
    }

    private function getRowActionsByVariant(string $variant, $model): array
    {
        $variantActions = array_filter($this->getTable()->getRowActions($model), fn (Action $action) => $action->{'is'.ucfirst($variant)}());

        return array_filter($variantActions, fn (Action $action) => ! $action->hasWhen() || $action->getWhen()($model));
    }
}
