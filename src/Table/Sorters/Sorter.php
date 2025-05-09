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
use Thinktomorrow\Chief\Forms\Fields\Locales\HasLocalizableProperties;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanBeDefault;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanHideFromView;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanShowActiveLabel;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasQuery;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasValue;

abstract class Sorter extends Component
{
    use CanBeDefault;
    use CanHideFromView;
    use CanShowActiveLabel;
    use HasComponentRendering;
    use HasDescription;
    use HasKey;
    use HasLabel;
    use HasLocalizableProperties;
    use HasQuery;
    use HasValue;
    use HasView;

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
