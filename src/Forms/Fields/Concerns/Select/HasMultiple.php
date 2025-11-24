<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

trait HasMultiple
{
    protected bool $allowMultiple = false;

    public function multiple(bool $flag = true): static
    {
        $this->allowMultiple = $flag;

        return $this;
    }

    public function allowMultiple(): bool
    {
        return $this->allowMultiple;
    }

    protected function prepForSavingMultipleValues()
    {
        $this->prepForSaving(function ($value) {
            if ($this->allowMultiple()) {
                return is_array($value) ? $value : (is_null($value) ? [] : [$value]);
            }

            return is_array($value) && count($value) ? reset($value) : $value;
        });
    }
}
