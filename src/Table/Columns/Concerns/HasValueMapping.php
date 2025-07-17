<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
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
            $columnItem->setOriginalValue($originalValue = $columnItem->getRawValue());

            $columnItem->value(
                call_user_func($valueMapResolver, $originalValue, $columnItem, $this->getModel())
            );
        }
    }

    /**
     * Preset mapping common Chief page states.
     */
    public function pageStates(): static
    {
        $this->mapValue(function ($rawValue, ColumnItem $columnItem, $model) {
            if ($model instanceof StatefulContract) {
                return $model->getStateConfig($columnItem->key)->getStateLabel($model);
            }

            if ($model instanceof Visitable) {
                if ($model->inOnlineState()) {
                    if ($model->urls->isNotEmpty()) {
                        return 'gepubliceerd';
                    } else {
                        return 'gepubliceerd zonder links';
                    }
                }
            }

            return match ($rawValue) {
                'published' => 'gepubliceerd',
                'draft' => 'draft',
                'archived' => 'gearchiveerd',
                default => $rawValue,
            };
        });

        return $this->mapVariant([
            'published' => 'blue',
            'draft' => 'grey',
            'archived' => 'red',
            'link ontbreekt' => 'orange',
        ]);
    }

    /** Preset for simple states */
    public function simpleStates(): static
    {
        $this->mapValue([
            'online' => 'online',
            'offline' => 'offline',
            'deleted' => 'verwijderd',
        ]);

        return $this->mapVariant([
            'online' => 'green',
            'offline' => 'red',
            'deleted' => 'grey',
        ]);
    }
}
