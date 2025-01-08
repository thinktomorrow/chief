<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\Columns\Header;

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
        $this->moveColumnsInOrder();

        return $this->headers;
    }
}
