<?php

namespace Thinktomorrow\Chief\Sites;

interface BelongsToSites
{
    /**
     * All sites for this model.
     * Each site here has info on whether it is the active for the model or not.
     */
    public function getSites(): ChiefSites;

    public function getSiteLocales(): array;
}
