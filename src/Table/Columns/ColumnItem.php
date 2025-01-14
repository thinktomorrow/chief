<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Columns;

use Illuminate\Contracts\Support\Htmlable;
use Stringable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasColumnName;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasDefault;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasItemMapping;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasItems;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasLink;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasTeaser;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasValueMapping;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasVariant;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasVariantMapping;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasView;

abstract class ColumnItem extends \Illuminate\View\Component implements Htmlable
{
    use HasItems;
    use HasItemMapping;
    use HasComponentRendering;
    use HasView;
    use HasCustomAttributes;
    use HasKey;
    use HasLabel;
    use HasDescription;

    use HasModel;
    use HasColumnName;
    use HasDefault;
    use HasLocalizableProperties;
    use HasTeaser;
    use HasValue {
        getValue as getDefaultValue;
    }
    use HasValueMapping;
    use HasVariantMapping;
    use HasVariant;
    use HasLink;

    public function __construct(string $key)
    {
        $this->label($key);

        $this->key(strtolower($key));
        $this->columnName(strtolower($key));

        $this->itemsFromKey($key);
    }

    public static function make(string|int $key): static
    {
        return new static((string) $key);
    }

    /**
     * Retrieve the renderable value for this column.
     */
    public function getValue(?string $locale = null): string|int|null|float|Stringable
    {
        // Retrieve value(s)
        $value = $this->getDefaultValue($locale);

        if (is_iterable($value)) {
            throw new \Exception('Non expected iterable value. The column item [' . $this->getKey() . '] is expected to have a scalar value.');
        }

        return $this->teaseValue($value);
    }
}
