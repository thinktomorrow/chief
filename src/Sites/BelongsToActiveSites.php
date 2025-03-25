<?php

namespace Thinktomorrow\Chief\Sites;

interface BelongsToActiveSites
{
    /** All sites where this model is active in. */
    public function getActiveSiteLocales(): array;

    public function setActiveSiteLocales(array $locales): void;

    public function addActiveSite(string $site): void;

    public function removeActiveSite(string $site): void;

    public function hasActiveSite($site): bool;
}
