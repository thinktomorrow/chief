<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasItems
{
    protected ?Closure $itemsResolver = null;

    public function items(array|Closure $itemsResolver): static
    {
        $this->itemsResolver = (! $itemsResolver instanceof Closure)
            ? fn (): iterable => $itemsResolver
            : $itemsResolver;

        return $this;
    }

    private function itemsFromRelationKey(string $key): static
    {
        if (! $this->isRelationKey($key)) {
            return $this;
        }

        $relationName = substr($key, 0, strpos($key, '.'));
        $relationAttribute = substr($key, strpos($key, '.') + 1);

        $this->items(function ($model) use ($relationName) {
            return $model->{$relationName};
        })->eachItem(function (ColumnItem $item, $value) use ($relationAttribute) {
            $item->value($value->{$relationAttribute});
        })->label($relationName);

        return $this;
    }

    private function isRelationKey(string $key): bool
    {
        return strpos($key, '.') !== false;
    }

    /**
     * An instance of ColumnItem is created for each item.
     * Also, any mapping/looping requested is applied to each item.
     */
    public function getItems(): iterable
    {
        return $this->resolveItems()
            ->each(function (ColumnItem $item) {
                $this->handleItemMapping($item);
            })
            ->each(function (ColumnItem $item) {
                $this->handleValueMapping($item);
            })
            ->each(function (ColumnItem $item) {
                $this->handleVariantMapping($item);
            })
            ->all();

    }

    private function resolveItems(): Collection
    {
        if (! $this->itemsResolver) {
            $result = [$this->replicateToItem($this->getValue())];
        } else {
            $result = call_user_func($this->itemsResolver, $this->getModel());
        }

        $result = $result instanceof Collection ? $result : collect($result);

        return $result->map(function (mixed $rawItem) {
            return (! $rawItem instanceof ColumnItem) ? $this->replicateToItem($rawItem) : $rawItem;
        });
    }

    private function replicateToItem($value): static
    {
        $item = static::make($this->getKey())->value($value);

        if ($this->tease) {
            $item->tease(...$this->tease);
        }

        return $item;
    }
}
