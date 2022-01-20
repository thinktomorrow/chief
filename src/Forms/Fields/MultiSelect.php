<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

class MultiSelect extends Select
{
    protected string $view = 'chief-forms::fields.multiselect';

    private bool $grouped = false;

    public function grouped(bool $grouped = true): static
    {
        $this->grouped = $grouped;

        return $this;
    }

    public function isGrouped(): bool
    {
        return $this->grouped;
    }
}
