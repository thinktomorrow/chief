<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Render;

interface ActiveContextOwner
{
    public function getActiveContextId(): ?string;
}
