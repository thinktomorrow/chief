<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasView;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasDefault;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPlaceholder;
use Thinktomorrow\Chief\Forms\Fields\Locales\HasLocalizableProperties;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasOrdinalLevel;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanBeDefault;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasQuery;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasValue;

abstract class Filter extends Component
{
    use CanBeDefault;
    use HasComponentRendering;
    use HasDefault;
    use HasDescription;
    use HasKey;
    use HasLabel;
    use HasLocalizableProperties;
    use HasOrdinalLevel;
    use HasPlaceholder;
    use HasQuery;
    use HasValue;
    use HasView;

    public function __construct(string $key)
    {
        $this->key($key);

        /**
         * Default query (for dynamic values) or collection filter.
         * This can be overridden in the specific filter.
         */
        $this->query(function ($query, $value) {
            if ($query instanceof Builder) {

                // blank value should not be applied
                if ($value === '') {
                    return;
                }

                if (is_array($value)) {
                    $query->whereIn($this->key, $value);
                } else {
                    $query->where($this->key, 'LIKE', '%'.$value.'%');
                    // $query->whereJsonLike($this->key, $value);
                }
            } else {
                return $query->filter(fn ($item) => str_contains(strtolower($item[$this->key]), strtolower($value)));
            }
        });
    }

    public static function make(string $key): static
    {
        return new static($key);
    }
}
