<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Thinktomorrow\Chief\TableNew\Filter;

class TextFilter extends AbstractFilter implements Filter
{
    public static function make(string $queryKey, \Closure $query): self
    {
        $filter = new static($queryKey, $query);
        $filter->view('chief-table-new::filters.input');

        return $filter;
    }
}
