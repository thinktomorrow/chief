<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

trait HasVariant
{
    protected ?string $variant = null;

    /**
     * TODO: This should not be just any string. It should be one of some possible strings. Maybe an enum?
     * At the moment, if the variant does not exist or is not set, the default variant is used.
     * For buttons, the default is 'outline-white'. For dropdown items, the default is 'grey'.
     */
    public function variant(?string $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }
}
