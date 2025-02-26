<?php

namespace Thinktomorrow\Chief\Sites;

interface BelongsToSites
{
    /** All sites where this model is active in. */
    public function getSiteIds(): array;

    public function setSiteIds(array $siteIds): void;
}
