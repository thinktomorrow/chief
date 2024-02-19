<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters\Presets;

use Thinktomorrow\Chief\TableNew\Filters\AbstractFilter;
use Thinktomorrow\Chief\TableNew\Filters\Filter;
use Thinktomorrow\Chief\TableNew\Filters\FilterType;

class RadioFilter extends AbstractFilter implements Filter
{
    private array $options = [];

    public static function make(string $queryKey, \Closure $query): self
    {
        $filter = new self(FilterType::RADIO, $queryKey, $query);

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
