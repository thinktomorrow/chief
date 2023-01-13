<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

/**
 * Positioning of forms on the admin page
 */
trait HasPosition
{
    protected ?string $position = null;

    public function position(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }
}
