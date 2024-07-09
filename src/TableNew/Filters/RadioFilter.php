<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;

class RadioFilter extends Filter
{
    use HasOptions;

//    protected string $view = 'chief-table-new::filters.radio';
    protected string $view = 'chief-table-new::filters.radio-slider';

//    public static function make(string $queryKey, \Closure $query): self
//    {
//        $filter = new static($queryKey, $query);
//
//        // $filter->view('chief-table-new::filters.radio');
//        $filter->view('chief-table-new::filters.radio-slider');
//
//        return $filter->value([]);
//    }
}
