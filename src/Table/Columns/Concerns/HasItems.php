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

    private function itemsFromKey(string $key): static
    {
        if ($this->isRelationKey($key)) {
            return $this->itemsFromRelationKey($key);
        }

        $this->items(function () {
            $value = $this->getRawValue();

            return is_iterable($value) ? $value : [$value];
        });

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
        })->mapValue(fn ($value) => is_array($value) ? $value[$relationAttribute] : $value->{$relationAttribute})
            ->label($relationName);

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
    public function getItems(): Collection
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
            });
    }

    private function resolveItems(): Collection
    {
        if (! $this->itemsResolver) {
            $result = [$this->replicateToItem($this->getRawValue())];
        } else {
            $result = call_user_func($this->itemsResolver, $this->getModel());
        }

        $result = $result instanceof Collection ? $result : (! is_iterable($result) ? collect([$result]) : collect($result));

        return $result->values()->map(function (mixed $rawItem) {
            return (! $rawItem instanceof ColumnItem) ? $this->replicateToItem($rawItem) : $rawItem;
        });
    }

    protected function replicateToItem($value): static
    {
        $item = static::make($this->getKey())->value($value);

        if ($this->locale) {
            $item->locale($this->locale);
        }

        if ($this->model) {
            $item->model($this->model);
        }

        if ($this->tease) {
            $item->tease(...$this->tease);
        }

        if ($this->link) {
            $item->link($this->link);
        }

        if ($this->openInNewTab) {
            $item->openInNewTab();
        }

        return $item;
    }
}
