<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasAcceptedMimeTypes
{
    protected array $acceptedMimeTypes = [];

    public function acceptedMimeTypes(array $acceptedMimeTypes): static
    {
        $this->acceptedMimeTypes = $acceptedMimeTypes;

        return $this;
    }

    public function getAcceptedMimeTypes(): array
    {
        return $this->acceptedMimeTypes;
    }
}
