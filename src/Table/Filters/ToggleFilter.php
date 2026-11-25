<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

class ToggleFilter extends Filter
{
    protected string $view = 'chief-table::filters.toggle';

    protected ?string $optionLabel = null;

    protected ?string $optionDescription = null;

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->query(function ($query) {
            if ($query instanceof Builder) {
                $query->where($this->key, true);

                return;
            }

            return $query->filter(fn ($item) => (bool) $item[$this->key]);
        });
    }

    public function default(int|bool|array|string|Closure|null $default): static
    {
        if (! is_bool($default)) {
            throw new InvalidArgumentException('Default value for Toggle filter must be a boolean.');
        }

        return $default ? $this->defaultOn() : $this->defaultOff();
    }

    /**
     * Without a default value, the filter starts switched off and is left out of the active filters.
     */
    public function defaultOff(): static
    {
        $this->default = false;
        $this->value = null;
        $this->valueGiven = false;

        return $this;
    }

    public function defaultOn(): static
    {
        $this->default = true;

        return $this->value(true);
    }

    public function optionLabel(string $optionLabel): static
    {
        $this->optionLabel = $optionLabel;

        return $this;
    }

    public function getOptionLabel(): ?string
    {
        return $this->optionLabel;
    }

    /**
     * The label shown next to the switch, falling back to the filter label.
     */
    public function getSwitchLabel(): string
    {
        return $this->optionLabel ?? $this->getLabel() ?? $this->getKey();
    }

    public function optionDescription(string $optionDescription): static
    {
        $this->optionDescription = $optionDescription;

        return $this;
    }

    public function getOptionDescription(): ?string
    {
        return $this->optionDescription;
    }

    /**
     * Interpret a filter value as switched on. Values restored
     * from the url arrive as strings such as 'true' or 'false'.
     */
    public function isOn(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function isOnByDefault(): bool
    {
        return $this->hasValue() && $this->isOn($this->getValue());
    }

    /**
     * Only apply the query when the toggle is switched on.
     */
    public function getQuery(): Closure
    {
        $query = parent::getQuery();

        return function ($builder, $value) use ($query) {
            if (! $this->isOn($value)) {
                return $builder instanceof Builder ? null : $builder;
            }

            return $query($builder, true);
        };
    }
}
