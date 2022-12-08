<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters\Presets;

use Thinktomorrow\Chief\ManagedModels\Filters\AbstractFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Filter;
use Thinktomorrow\Chief\ManagedModels\Filters\FilterType;

class HiddenFilter extends AbstractFilter implements Filter
{
    public static function make(string $queryKey, \Closure $query): self
    {
        return new self(FilterType::HIDDEN, $queryKey, $query);
    }

    public function applicable(array $parameterBag): bool
    {
        return true;
    }

    public function render(array $parameterBag): string
    {
        return '';
    }
}
