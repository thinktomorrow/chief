<?php

namespace Thinktomorrow\Chief\TableNew\Columns;

use Illuminate\Support\Collection;

class ColumnText extends ColumnItem
{
    protected string $view = 'chief-table-new::columns.text';

    /**
     * Retrieve a value for this column.
     */
    public function getValue(?string $locale = null): mixed
    {
        // Retrieve value(s)
        $value = $this->getDefaultValue($locale);

        if (is_iterable($value)) {
            throw new \Exception('Should not be iterable...');
        }

        // Value map if any
        $value = $this->valueMap[$value] ?? $value;

        return $this->teaseValue($value);
    }

    public function getValues(?string $locale = null): array
    {
        // Retrieve value(s)
        $value = $this->getDefaultValue($locale);

        if (! is_iterable($value)) {
            $value = [$value];
        }

        return $this->expandValues(
            $value instanceof Collection ? $value->all() : $value,
            $locale
        );
    }

    private function expandValues(array $values, ?string $locale = null): array
    {
        $values = $this->handleEachValue($values);

        return array_map(function ($value) {
            return $this->replicate()
                ->value($value);
        }, $values);
    }

    protected function replicate(): static
    {
        return clone $this;
    }
}
