<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasWireModelType
{
    protected string $wireModelType = 'defer';

    private function wireModelType(string $type): static
    {
        // validate the type
        if (! in_array($type, ['live', 'defer'])) {
            throw new \InvalidArgumentException("Invalid wire model type: $type. Allowed types are 'live' or 'defer'.");
        }

        $this->wireModelType = $type;

        return $this;
    }

    public function enableWireModelLive(): static
    {
        $this->wireModelType = 'live';

        return $this;
    }

    public function enableWireModelDefer(): static
    {
        $this->wireModelType = 'defer';

        return $this;
    }

    public function getWireModelType(): string
    {
        return $this->wireModelType;
    }
}
