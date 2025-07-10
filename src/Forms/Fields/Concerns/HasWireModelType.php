<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;

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
        return $this->wireModelType === 'defer' ? 'wire:model' : 'wire:model.'.$this->wireModelType.'.debounce.350ms';
    }

    public function getWireModelValue(?string $locale = null): string
    {
        return LivewireFieldName::get($this->getName($locale ?? null));
    }
}
