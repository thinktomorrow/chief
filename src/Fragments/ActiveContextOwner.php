<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

interface ActiveContextOwner
{
    public function getActiveContextId(): ?string;
}
