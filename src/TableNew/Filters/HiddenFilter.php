<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Illuminate\Http\Request;

class HiddenFilter extends Filter
{
    public function applicable(Request $request): bool
    {
        return true;
    }

    public function render(): string
    {
        return '';
    }
}
