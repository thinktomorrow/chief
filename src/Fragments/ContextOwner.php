<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

interface ContextOwner
{
    public function activeContextId(string $locale): ?string;
}
