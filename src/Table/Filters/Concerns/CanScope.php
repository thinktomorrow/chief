<?php

namespace Thinktomorrow\Chief\Table\Filters\Concerns;

trait CanScope
{
    private bool $scopesTableState = false;

    private ?array $scopedTableStateKeys = null;

    /**
     * Mark this filter as a table-state scope, optionally limited to specific filter or sorter keys.
     * Multiple scoped filters are combined into one table scope for filters and sorters.
     */
    public function scoped(bool|array $keys = true): static
    {
        $this->scopesTableState = $keys !== false;
        $this->scopedTableStateKeys = is_array($keys) ? array_values($keys) : null;

        return $this;
    }

    /**
     * Determine if this filter defines a separate table-state scope.
     */
    public function scopesTableState(): bool
    {
        return $this->scopesTableState;
    }

    /**
     * Determine if the given filter or sorter key should be stored per value of this scope filter.
     */
    public function scopesTableStateKey(string $key): bool
    {
        if (! $this->scopesTableState || $this->getKey() === $key) {
            return false;
        }

        if ($this->scopedTableStateKeys === null) {
            return true;
        }

        return in_array($key, $this->scopedTableStateKeys, true);
    }
}
