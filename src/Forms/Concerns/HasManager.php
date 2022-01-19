<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

use Thinktomorrow\Chief\Managers\Manager;

trait HasManager
{
    protected ?Manager $manager = null;

    public function manager(Manager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }
}
