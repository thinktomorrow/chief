<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasItemMapping
{
    private array $itemMapResolvers = [];

    public function eachItem(Closure $itemMapResolver): static
    {
        $this->itemMapResolvers[] = $itemMapResolver;

        return $this;
    }

    protected function handleItemMapping(ColumnItem $columnItem): void
    {
        foreach ($this->itemMapResolvers as $itemMapResolver) {
            call_user_func($itemMapResolver, $columnItem, $columnItem->getRawValue(), $this->getModel(), $this);
        }
    }
}
