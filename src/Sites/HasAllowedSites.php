<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;

/**
 * Model that have links that refer to site and context. Like pages.
 */
interface HasAllowedSites
{
    /** Can the admin change selected sites for this model? */
    public function allowSiteSelection(): bool;

    /** All sites where this model is active in. */
    public function getAllowedSites(): array;

    public function setAllowedSites(array $locales): void;

    public function addAllowedSite(string $site): void;

    public function removeAllowedSite(string $site): void;

    public function scopeByAllowedSite(Builder $query, ?string $site = null): void;

    public function scopeByAllowedSiteOrNone(Builder $query, ?string $site = null): void;
}
