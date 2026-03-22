<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Closure;

class ToggleFilter extends OptionFilter
{
    public static function make(string $queryKey, ?Closure $query = null): static
    {
        $filter = new static($queryKey);

        if ($query) {
            $filter->query($query);
        }

        $filter->displayAsToggle();

        return $filter;
    }
}
