<?php

namespace Thinktomorrow\Chief\Sites;

interface BelongsToSites
{
    /** All sites where this model is active in. */
    public function getSiteLocales(): array;

    public function setSiteLocales(array $locales): void;
}
