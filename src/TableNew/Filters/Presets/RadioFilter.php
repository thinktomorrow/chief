<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters\Presets;

use Thinktomorrow\Chief\TableNew\Filters\AbstractFilter;
use Thinktomorrow\Chief\TableNew\Filters\Filter;

class RadioFilter extends AbstractFilter implements Filter
{
    private array $options = [];

    public static function make(string $queryKey, \Closure $query): self
    {
        $filter = new static($queryKey, $query);

        // $filter->view('chief-table-new::filters.radio');
        $filter->view('chief-table-new::filters.radio-slider');

        return $filter->value([]);
    }

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    protected function viewData(): array
    {
        return array_merge(parent::viewData(), [
            'options' => $this->options,
        ]);
    }
}
