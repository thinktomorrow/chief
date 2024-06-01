<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

interface ContextOwner
{
    /**
     * List of allowed sections (root fragments) for the context of this owner.
     */
    public function allowedSections(): array;
}
