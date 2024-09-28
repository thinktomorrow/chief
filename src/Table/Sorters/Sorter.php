<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Sorters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasView;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanBeDefault;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanHideFromView;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanShowActiveLabel;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasQuery;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasValue;

abstract class Sorter extends Component
{
    use HasComponentRendering;
    use HasLocalizableProperties;
    use HasView;
    use HasKey;
    use HasLabel;
    use HasDescription;
    use HasValue;
    use CanBeDefault;
    use CanShowActiveLabel;
    use CanHideFromView;

    use HasQuery;

    public function __construct(string $key)
    {
        $this->key($key);
        $this->value('asc');

        /**
         * Default query (for dynamic values) or collection sorter.
         * This can be overridden in the specific sorter.
         */
        $this->query(function ($query, $value) {
            if ($query instanceof Builder) {
                $query->orderBy($this->key, $value);
            } else {
                return $query->sortBy($this->key, SORT_REGULAR, $value === 'desc');
            }
        });
    }

    public static function make(string $key): static
    {
        return new static($key);
    }
}
