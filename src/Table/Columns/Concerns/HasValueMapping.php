<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasValueMapping
{
    private ?Closure $valueMapResolver = null;

    public function mapValue(array|Closure $valueMapResolver): static
    {
        if ($valueMapResolver instanceof Closure) {
            $this->valueMapResolver = $valueMapResolver;
        } else {
            $this->valueMapResolver = function (ColumnItem $columnItem) use ($valueMapResolver) {

                $originalValue = $columnItem->getValue();

                if (is_scalar($originalValue) && isset($valueMapResolver[$originalValue])) {
                    $columnItem->value($valueMapResolver[$originalValue]);
                }
            };
        }

        return $this;
    }

    protected function handleValueMapping(ColumnItem $columnItem): void
    {
        if ($this->valueMapResolver) {
            call_user_func($this->valueMapResolver, $columnItem, $columnItem->getValue(), $this->getModel(), $this);
        }
    }

    /**
     * Preset mapping common Chief page states.
     */
    public function pageStates(): static
    {
        $this->mapValue([
            'published' => 'online',
            'draft' => 'offline',
            'archived' => 'archived',
        ]);

        return $this->mapVariant([
            'online' => 'green',
            'offline' => 'red',
            'archived' => 'red',
        ]);
    }
}
