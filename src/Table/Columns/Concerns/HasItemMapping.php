<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasItemMapping
{
    private ?Closure $itemMapResolver = null;

    public function eachItem(Closure $itemMapResolver): static
    {
        $this->itemMapResolver = $itemMapResolver;

        return $this;
    }

    protected function handleItemMapping(ColumnItem $columnItem): void
    {
        if ($this->itemMapResolver) {
            call_user_func($this->itemMapResolver, $columnItem, $columnItem->getValue(), $this->getModel(), $this);
        }
    }
}
