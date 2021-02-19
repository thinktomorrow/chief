<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters\Presets;

use Thinktomorrow\Chief\ManagedModels\Filters\AbstractFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Filter;
use Thinktomorrow\Chief\ManagedModels\Filters\FilterType;

class SelectFilter extends AbstractFilter implements Filter
{
    private array $options = [];

    private bool $multiple = false;

    public static function make(string $queryKey, \Closure $query): self
    {
        return new self(FilterType::SELECT, $queryKey, $query);
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


    protected function viewData(): array
    {
        return array_merge(parent::viewData(), [
            'options' => $this->options,
            'multiple' => $this->multiple,
        ]);
    }
}
