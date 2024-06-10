<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

interface ContextOwner
{
    /**
     * List of allowed fragments for all contexts of this owner.
     */
    public function allowedFragments(): array;
}
