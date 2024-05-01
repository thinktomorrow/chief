<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

trait ActiveContextOwnerDefaults
{
    public function getActiveContextId(): ?string
    {
        // get the context id for this context owner for the given locale
        return $this->context_id;
    }
}
