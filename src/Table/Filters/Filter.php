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
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPlaceholder;
use Thinktomorrow\Chief\Table\Actions\Concerns\HasVariant;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanBeDefault;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasQuery;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasValue;

abstract class Filter extends Component
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
    use CanBeDefault;
    use HasVariant;

    use HasQuery;

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
                    //$query->whereJsonLike($this->key, $value);
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

    //    protected function viewData(): array
    //    {
    //        return [
    //            'id' => $this->queryKey,
    //            'name' => $this->queryKey,
    //            'label' => $this->label,
    //            'description' => $this->description,
    //            'value' => $this->getValue(),
    //            'placeholder' => $this->placeholder,
    //            'default' => $this->default,
    //        ];
    //    }
}
