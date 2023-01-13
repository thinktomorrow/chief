<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasDescription
{
    protected ?string $description = null;

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
