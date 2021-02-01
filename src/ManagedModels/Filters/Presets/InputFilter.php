<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters\Presets;

use Thinktomorrow\Chief\ManagedModels\Filters\Filter;
use Thinktomorrow\Chief\ManagedModels\Filters\FilterType;
use Thinktomorrow\Chief\ManagedModels\Filters\AbstractFilter;

class InputFilter extends AbstractFilter implements Filter
{
    public static function make(string $queryKey, \Closure $query): self
    {
        return new self(FilterType::INPUT, $queryKey, $query);
    }
}
