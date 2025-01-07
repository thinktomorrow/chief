<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasValueMapping
{
    private array $valueMapResolvers = [];

    public function mapValue(array|Closure $valueMapResolver): static
    {
        if ($valueMapResolver instanceof Closure) {
            $this->valueMapResolvers[] = $valueMapResolver;
        } else {
            $this->valueMapResolvers[] = function ($rawValue, ColumnItem $columnItem) use ($valueMapResolver) {
                if (is_scalar($rawValue)) {
                    return $valueMapResolver[$rawValue] ?? $rawValue;
                }

                return $rawValue;
            };
        }

        return $this;
    }

    protected function handleValueMapping(ColumnItem $columnItem): void
    {
        foreach ($this->valueMapResolvers as $valueMapResolver) {
            $columnItem->value(
                call_user_func($valueMapResolver, $columnItem->getRawValue(), $columnItem, $this->getModel())
            );

        }
    }

    /**
     * Preset mapping common Chief page states.
     */
    public function pageStates(): static
    {
//        $this->mapValue([
//            'published' => 'online',
//            'draft' => 'offline',
//            'archived' => 'archived',
//        ]);

        $this->mapValue(function ($rawValue, ColumnItem $columnItem, $model) {
            if($model instanceof Visitable) {

                if ($model->inOnlineState()) {
                    if ($model->urls->isNotEmpty()) {
                        return 'online';
                    } else {
                        return 'link ontbreekt';
                    }
                }

                return 'offline';
            }

            return match($rawValue) {
                'published' => 'online',
                'draft' => 'offline',
                'archived' => 'archived',
                default => $rawValue,
            };
        });

        return $this->mapVariant([
            'online' => 'green',
            'offline' => 'red',
            'archived' => 'red',
            'link ontbreekt' => 'orange',
        ]);
    }
}
