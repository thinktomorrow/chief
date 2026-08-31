<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Columns;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\Component;
use Stringable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasColumnName;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasDefault;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;
use Thinktomorrow\Chief\Forms\Fields\Locales\HasLocalizableProperties;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasColumnSelection;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasItemMapping;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasItems;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasLink;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasLocale;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasTeaser;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasValueMapping;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasVariant;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasVariantMapping;
use Thinktomorrow\Chief\Table\Columns\Concerns\HasView;

abstract class ColumnItem extends Component implements Htmlable
{
    use HasColumnName;
    use HasColumnSelection;
    use HasComponentRendering;
    use HasCustomAttributes;
    use HasDefault;
    use HasDescription;
    use HasItemMapping;
    use HasItems;
    use HasKey;
    use HasLabel;
    use HasLink;
    use HasLocale;
    use HasLocalizableProperties;
    use HasModel;
    use HasTeaser;
    use HasValue {
        value as setDefaultValue;
        getValue as getDefaultValue;
    }
    use HasValueMapping;
    use HasVariant;
    use HasVariantMapping;
    use HasView;

    /**
     * Specify locale in which the value has been given. This is
     * useful to distinguish between localized values.
     */
    protected ?string $valueGivenForLocale = null;

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

    public function value(mixed $value): static
    {
        $this->setDefaultValue($value);

        //        if (isset($this->locale)) {
        //            $this->valueGivenForLocale = $this->locale;
        //        }

        return $this;
    }

    private bool $valueMarkedAsResolved = false;

    public function markValueAsResolved(): static
    {
        $this->valueMarkedAsResolved = true;

        return $this;
    }

    public function isValueMarkedAsResolved(): bool
    {
        return $this->valueMarkedAsResolved;
    }

    /**
     * Retrieve the render value for this column.
     */
    public function getValue(?string $locale = null): string|int|null|float|Stringable
    {
        // Force refetch of value in case the locale has changed.
        if (($locale || $this->valueGivenForLocale) && $this->valueGivenForLocale !== $locale) {
            $this->valueGiven = false;
            $this->valueGivenForLocale = $locale;
        }

        if ($this->isValueMarkedAsResolved()) {
            $value = $this->value;
        } else {
            $value = $this->getDefaultValue($locale);
        }

        $this->verifyValueCanBeRendered($value);

        return $this->teaseValue($value);
    }

    private function verifyValueCanBeRendered($value): void
    {
        if (is_iterable($value)) {
            throw new \InvalidArgumentException($this->invalidValueMessage($value));
        }

        if (! is_string($value) && ! is_int($value) && ! is_float($value) && ! is_null($value) && ! $value instanceof Stringable) {
            throw new \InvalidArgumentException($this->invalidValueMessage($value));
        }
    }

    private function invalidValueMessage(mixed $value): string
    {
        $model = $this->getModel();
        $modelType = is_object($model) ? $model::class : get_debug_type($model);

        return 'Table column ['.$this->getKey().'] could not render attribute ['.$this->getColumnName().']'
            .' from model ['.$modelType.']. Expected a scalar, null, or Stringable value, but resolved ['.get_debug_type($value).'].'
            .' Check for a conflicting model method or property, or define an explicit value() or items() resolver.';
    }
}
