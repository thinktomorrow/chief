<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;

interface BelongsToSites
{
    /** All sites where this model is active in. */
    public function getSiteLocales(): array;

    public function setSiteLocales(array $locales): void;

    public function scopeBySiteLocale(Builder $query, string $site): void;

    public function scopeBySiteLocaleOrNone(Builder $query, string $site): void;
}
