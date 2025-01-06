<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Closure;

class ToggleFilter extends OptionFilter
{
    public static function make(string $queryKey, Closure $query): self
    {
        $filter = new static($queryKey, $query);
        $filter->displayAsToggle();

        return $filter;
    }
}
