<?php

namespace Thinktomorrow\Chief\Sites;

interface MultiSiteable
{
    /**
     * All available sites for this model.
     * Each locale here represents a site.
     */
    public function getLocales(): array;

    public function saveLocales(array $locales): void;
}
