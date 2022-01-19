<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasLabel
{
    protected ?string $label = null;

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
}
