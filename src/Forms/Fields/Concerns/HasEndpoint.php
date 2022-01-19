<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasEndpoint
{
    private ?string $endpoint = null;

    public function endpoint(string $endpoint): static
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }
}
