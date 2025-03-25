<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\Sites\Locales\LocalizedDefaults;

trait BelongsToSitesDefaults
{
    use LocalizedDefaults;

    /**
     * This method is called on an Eloquent model to initialize the localized defaults.
     */
    public function initializeBelongsToSitesDefaults(): void
    {
        $this->mergeCasts(['sites' => 'array']);
    }

    public function getSiteLocales(): array
    {
        return $this->sites ?? [];
    }

    public function setSiteLocales(array $locales): void
    {
        $this->sites = $locales;
    }

    public function scopeBySite(Builder $query, string $site): void
    {
        $query->whereJsonContains($this->getTable().'.sites', $site);
    }

    public function scopeBySiteOrNone(Builder $query, string $site): void
    {
        $query->when($site, fn ($q) => $q->where(function ($q) use ($site) {
            $q->whereJsonContains($this->getTable().'.sites', $site)
                ->orWhereNull($this->getTable().'.sites')
                ->orWhereJsonLength($this->getTable().'.sites', '=', 0);
        }));
    }
}
