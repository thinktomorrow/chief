<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Illuminate\Http\Request;

class HiddenFilter extends Filter
{
    protected string $view = 'chief-table::filters.hidden';

    public function applicable(Request $request): bool
    {
        return true;
    }
}
