<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

class CheckboxFilter extends SelectFilter
{
    protected string $view = 'chief-table::filters.checkbox';

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->multiple();
    }
}
