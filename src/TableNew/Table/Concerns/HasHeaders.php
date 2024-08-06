<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Thinktomorrow\Chief\TableNew\Columns\Header;

trait HasHeaders
{
    private array $headers = [];

    public function headers(array $headers): static
    {
        $this->headers = array_map(fn ($header) => (! $header instanceof Header) ? Header::make($header) : $header, $headers);

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
