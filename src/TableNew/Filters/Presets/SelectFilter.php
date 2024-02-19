<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters\Presets;

use Closure;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\TableNew\Filters\AbstractFilter;
use Thinktomorrow\Chief\TableNew\Filters\Filter;
use Thinktomorrow\Chief\TableNew\Filters\FilterType;

class SelectFilter extends AbstractFilter implements Filter
{
    private array $options = [];
    private bool $multiple = false;

    public static function make(string $queryKey, Closure $query): self
    {
        $filter = new static($queryKey, $query);
        $filter->view('chief-table-new::filters.select');

        return $filter;
    }

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    /** @deprecated - grouped is now auto determined based on options input. */
    public function grouped(bool $grouped = true): static
    {
        $this->grouped = $grouped;

        return $this;
    }

    protected function viewData(): array
    {
        return array_merge(parent::viewData(), [
            'options' => PairOptions::toMultiSelectPairs($this->options),
            'multiple' => $this->multiple,
        ]);
    }
}
