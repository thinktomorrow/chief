<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Filters\Types;

use Closure;
use Thinktomorrow\Chief\Filters\Filter;

class InputFilter extends Filter
{
    public static function make(string $key)
    {
        return new static(new FilterType(FilterType::INPUT), $key);
    }

    public function apply($value = null): Closure
    {
        return $this->query && $this->query instanceof Closure ? $this->query : function ($query) {
            return $query;
        };
    }
}
