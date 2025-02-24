<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ActiveContext;

interface ActiveContextOwner
{
    public function getActiveContextId(): ?string;
}
