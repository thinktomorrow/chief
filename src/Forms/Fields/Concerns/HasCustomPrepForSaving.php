<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Thinktomorrow\Chief\Forms\Fields\Field;

trait HasCustomPrepForSaving
{
    protected ?\Closure $prepForSaving = null;

    /**
     * Register a preparation for saving callback.
     *
     * @param  \Closure(Field $field, mixed $value, array $input, ?string $locale): void  $prepareModelValue
     * @return $this
     */
    public function prepForSaving(\Closure $prepareModelValue): static
    {
        $this->prepForSaving = $prepareModelValue;

        return $this;
    }

    public function hasPrepForSaving(): bool
    {
        return ! is_null($this->prepForSaving);
    }

    public function getPrepForSaving(): ?\Closure
    {
        if (! $this->hasPrepForSaving()) {
            return null;
        }

        return $this->prepForSaving;
    }
}
