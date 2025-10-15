<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

use Closure;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;

trait HasItems
{
    protected ?Closure $itemsResolver = null;

    /**
     * If the user has their own resolver, we won't try to refresh the data when
     * e.g. locale changes. But rather let the custom resolver handle that.
     */
    protected bool $usesCustomItemsResolver = false;

    public function values(array|Closure $itemsResolver): static
    {
        return $this->items($itemsResolver);
    }

    public function items(array|Closure $itemsResolver): static
    {
        $this->setItemsResolver($itemsResolver);

        $this->usesCustomItemsResolver = true;

        return $this;
    }

    private function itemsFromKey(string $key): static
    {
        if ($this->isRelationKey($key)) {
            return $this->itemsFromRelationKey($key);
        }

        $this->setItemsResolver(function ($model = null, $locale = null) {
            $value = $this->getRawValue($locale);

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

        $this->setItemsResolver(function ($model = null, $locale = null) use ($relationName) {
            return $model->{$relationName};
        })->mapValue(fn ($value) => is_array($value) ? $value[$relationAttribute] : $value->{$relationAttribute})
            ->label($relationName);

        return $this;
    }

    private function setItemsResolver(array|Closure $itemsResolver): static
    {
        $this->itemsResolver = (! $itemsResolver instanceof Closure)
            ? fn ($model = null, $locale = null): iterable => $itemsResolver
            : $itemsResolver;

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
            $result = [$this->replicateToItem($this->getRawValue($this->getLocale()))];
        } else {
            $result = call_user_func($this->itemsResolver, $this->getModel(), $this->getLocale());
        }

        $result = $result instanceof Collection ? $result : (! is_iterable($result) ? collect([$result]) : collect($result));

        /**
         * We ensure all resolved items are instances of ColumnItem.
         *
         * If not, we replicate the current column item to a new instance. Also, we mark the value as
         * resolved with a custom resolver, so it won't be resolved again when e.g. locale changes.
         */
        return $result->values()->map(function (mixed $rawItem) {
            $item = (! $rawItem instanceof ColumnItem) ? $this->replicateToItem($rawItem) : $rawItem;

            if ($this->usesCustomItemsResolver) {
                $item->markValueAsResolved();
            }

            return $item;
        });
    }

    protected function replicateToItem($value): static
    {
        $item = static::make($this->getKey())
            ->value($value);

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

        if ($this->variant) {
            $item->variant($this->variant);
        }

        return $item;
    }
}
