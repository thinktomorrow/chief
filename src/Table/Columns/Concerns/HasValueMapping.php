<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasValueMapping
{
    private array $valueMapResolvers = [];

    public function mapValue(array|Closure $valueMapResolver): static
    {
        if ($valueMapResolver instanceof Closure) {
            $this->valueMapResolvers[] = $valueMapResolver;
        } else {
            $this->valueMapResolvers[] = function (ColumnItem $columnItem) use ($valueMapResolver) {

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
        foreach ($this->valueMapResolvers as $valueMapResolver) {
            call_user_func($valueMapResolver, $columnItem, $columnItem->getValue(), $this->getModel(), $this);
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
