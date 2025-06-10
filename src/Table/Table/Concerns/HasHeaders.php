<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\Columns\Header;

trait HasHeaders
{
    private array $headers = [];

    public function headers(array $headers): static
    {
        foreach ($headers as $key => $header) {

            // If the header is already an instance of Header, we can skip it
            if ($header instanceof Header) {
                continue;
            }

            if (is_string($key)) {
                // If the key is a string, we assume the value is a label and we create a header with the key as label
                $headers[$key] = Header::makeHeader($key, $header);
            } elseif (is_int($key)) {
                // If the key is an int, we create the label from the key
                $headers[$key] = Header::make($header);
            }
        }

        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
