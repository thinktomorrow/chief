<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;

/**
 * Model that keeps reference to which sites it is active.
 */
interface HasActiveSites
{
    /** All sites where this model is active in. */
    public function getActiveSites(): array;

    public function addActiveSite(string $site): void;

    public function removeActiveSite(string $site): void;

    public function hasActiveSite($site): bool;

    public function scopeByActiveSite(Builder $query, string $site): void;

    public function scopeByActiveSiteOrNone(Builder $query, string $site): void;
}
