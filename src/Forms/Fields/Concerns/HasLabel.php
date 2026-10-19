<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasLabel
{
    protected ?string $label = null;

    protected bool $hasExplicitLabel = false;

    public function label(string $label): static
    {
        $this->label = $label;

        $this->hasExplicitLabel = true;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    /** Indicates that the label was set via label(), rather than as a default. */
    protected function hasExplicitLabel(): bool
    {
        return $this->hasExplicitLabel;
    }
}
