<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\Fields\Field;

trait HasCustomFillForSaving
{
    protected ?\Closure $fillForSaving = null;

    /**
     * Register a custom fill for saving callback.
     *
     * @param  Closure(Model $model, Field $field, array $input, array $files): void  $fillForSaving
     * @return $this
     */
    public function fillForSaving(\Closure $fillForSaving): static
    {
        $this->fillForSaving = $fillForSaving;

        return $this;
    }

    public function hasFillForSaving(): bool
    {
        return ! is_null($this->fillForSaving);
    }

    public function getFillForSaving(): ?\Closure
    {
        if (! $this->hasFillForSaving()) {
            return null;
        }

        return $this->fillForSaving;
    }
}
