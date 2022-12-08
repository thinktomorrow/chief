<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters\Presets;

use Thinktomorrow\Chief\ManagedModels\Filters\AbstractFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Filter;
use Thinktomorrow\Chief\ManagedModels\Filters\FilterType;

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

    protected function viewData(array $parameterBag): array
    {
        return array_merge(parent::viewData($parameterBag), [
            'options' => $this->options,
        ]);
    }
}
