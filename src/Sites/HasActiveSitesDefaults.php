<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasActiveSitesDefaults
{
    /**
     * This method is called on an Eloquent model to initialize the localized defaults.
     */
    public function initializeHasActiveSitesDefaults(): void
    {
        $this->mergeCasts(['active_sites' => 'array']);
    }

    public function getActiveSites(): array
    {
        return $this->active_sites ?? [];
    }

    public function hasActiveSite($site): bool
    {
        return in_array($site, $this->active_sites ?? []);
    }

    public function addActiveSite(string $site): void
    {
        $this->active_sites = array_unique(array_merge($this->active_sites ?? [], [$site]));
    }

    public function removeActiveSite(string $site): void
    {
        $this->active_sites = array_values(array_diff($this->active_sites ?? [], [$site]));
    }

    public function scopeByActiveSite(Builder $query, string $site): void
    {
        $query->whereJsonContains($this->getTable().'.active_sites', $site);
    }

    public function scopeByActiveSiteOrNone(Builder $query, string $site): void
    {
        $query->when($site, fn ($q) => $q->where(function ($q) use ($site) {
            $q->whereJsonContains($this->getTable().'.active_sites', $site)
                ->orWhereNull($this->getTable().'.active_sites')
                ->orWhereJsonLength($this->getTable().'.active_sites', '=', 0);
        }));

        if (DB::getDriverName() === 'sqlite') {
            $query->orderByRaw("
            CASE
                WHEN active_sites IS NULL OR active_sites = '[]' THEN 1
                ELSE 0
            END
        ");
        } else {
            $query->orderByRaw('
            CASE
                WHEN JSON_TYPE(active_sites) IS NULL OR JSON_LENGTH(active_sites) = 0 THEN 1
                ELSE 0
            END
        ');
        }
    }
}
