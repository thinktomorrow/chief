<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Filters\Types;

use Thinktomorrow\Chief\Filters\Filter;

class InputFilter extends Filter
{
    public static function make(string $key)
    {
        return new static(new FilterType(FilterType::INPUT), $key);
    }
}
