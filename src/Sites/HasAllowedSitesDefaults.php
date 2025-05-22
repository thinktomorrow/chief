<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasAllowedSitesDefaults
{
    public function allowSiteSelection(): bool
    {
        return config('chief.allow_site_selection', false);
    }

    /**
     * This method is called on an Eloquent model to initialize the localized defaults.
     */
    public function initializeHasAllowedSitesDefaults(): void
    {
        $this->mergeCasts(['allowed_sites' => 'array']);
    }

    public function getAllowedSites(): array
    {
        return ($this->allowed_sites && ! empty($this->allowed_sites)) ? $this->allowed_sites : ChiefSites::locales();
    }

    public function setAllowedSites(array $allowed_sites): void
    {
        $this->allowed_sites = $allowed_sites;
    }

    public function addAllowedSite(string $site): void
    {
        $this->allowed_sites = array_unique(array_merge($this->allowed_sites ?? [], [$site]));
    }

    public function removeAllowedSite(string $site): void
    {
        $this->allowed_sites = array_values(array_diff($this->allowed_sites ?? [], [$site]));
    }

    public function scopeByAllowedSite(Builder $query, ?string $site = null): void
    {
        if (! $site) {
            $site = app()->getLocale();
        }

        $query->whereJsonContains($this->getTable().'.allowed_sites', $site);
    }

    public function scopeByAllowedSiteOrNone(Builder $query, ?string $site = null): void
    {
        if (! $site) {
            $site = app()->getLocale();
        }

        $query->when($site, fn ($q) => $q->where(function ($q) use ($site) {
            $q->whereJsonContains($this->getTable().'.allowed_sites', $site)
                ->orWhereNull($this->getTable().'.allowed_sites')
                ->orWhereJsonLength($this->getTable().'.allowed_sites', '=', 0);
        }));

        if (DB::getDriverName() === 'sqlite') {
            $query->orderByRaw("
            CASE
                WHEN allowed_sites IS NULL OR allowed_sites = '[]' THEN 1
                ELSE 0
            END
        ");
        } else {
            $query->orderByRaw('
            CASE
                WHEN JSON_TYPE(allowed_sites) IS NULL OR JSON_LENGTH(allowed_sites) = 0 THEN 1
                ELSE 0
            END
        ');
        }
    }
}
