<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Illuminate\Database\Eloquent\Builder;

class RadioFilter extends SelectFilter
{
    protected string $view = 'chief-table::filters.radio';

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->query(function ($query, $value) {
            if ($query instanceof Builder) {
                $query->where($this->key, $value);

                return;
            }

            return $query->filter(fn ($item) => $item[$this->key] == $value);
        });
    }
}
