<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;

class CheckboxFilter extends SelectFilter
{
    use HasOptions;
    use HasMultiple;

    protected string $view = 'chief-table::filters.checkbox';

    //    public static function make(string $queryKey, \Closure $query): self
    //    {
    //        $filter = new static($queryKey, $query);
    //        $filter->view('chief-table::filters.checkbox')
    //               ->value([]);
    //
    //        return $filter;
    //    }

}
