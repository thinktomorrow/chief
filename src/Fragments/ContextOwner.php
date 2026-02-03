<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

interface ContextOwner
{
    /**
     * Show the context management UI for this owner.
     */
    public function allowContexts(): bool;

    /**
     * List of allowed fragments for all contexts of this owner.
     */
    public function allowedFragments(): array;

    /**
     * Can the admin manage multiple contexts for this model
     */
    public function allowMultipleContexts(): bool;
}
