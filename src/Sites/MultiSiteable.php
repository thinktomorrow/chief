<?php

namespace Thinktomorrow\Chief\Sites;

interface MultiSiteable
{
    /**
     * All available sites for this model.
     * Each locale here represents a site.
     */
    public function getSiteLocales(): array;

    public function saveSiteLocales(array $locales): void;
}
