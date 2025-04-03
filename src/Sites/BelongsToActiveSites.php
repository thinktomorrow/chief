<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;

interface BelongsToActiveSites
{
    /** All sites where this model is active in. */
    public function getActiveSiteLocales(): array;

    public function setActiveSiteLocales(array $locales): void;

    public function addActiveSiteLocale(string $site): void;

    public function removeActiveSiteLocale(string $site): void;

    public function hasActiveSiteLocale($site): bool;

    public function scopeByActiveSiteLocale(Builder $query, string $site): void;

    public function scopeByActiveSiteLocaleOrNone(Builder $query, string $site): void;
}
