<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\TableNew\Filter;

class HiddenFilter extends AbstractFilter implements Filter
{
    public static function make(string $queryKey, \Closure $query): self
    {
        return new self(FilterType::HIDDEN, $queryKey, $query);
    }

    public function applicable(Request $request): bool
    {
        return true;
    }

    public function render(): string
    {
        return '';
    }
}
