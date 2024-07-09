<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Sorters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasView;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasDefault;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPlaceholder;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;
use Thinktomorrow\Chief\TableNew\Concerns\HasQuery;

abstract class Sorter extends Component
{
    use HasComponentRendering;
    use HasLocalizableProperties;
    use HasView;
    use HasKey;
    use HasLabel;
    use HasDescription;
    use HasPlaceholder;
    use HasValue;
    use HasDefault;

    use HasQuery;

    public function __construct(string $key)
    {
        $this->key($key);

        /**
         * Default query (for dynamic values) or collection sorter.
         * This can be overridden in the specific sorter.
         */
        $this->query(function($query, $value) {
            if($query instanceof Builder) {
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

    public function getValue(?string $locale = null): mixed
    {
        return old($this->key, request()->input($this->key, $this->value));
    }
}
