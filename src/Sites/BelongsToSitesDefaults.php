<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToSitesDefaults
{
    //    use LocalizedDefaults;

    /**
     * This method is called on an Eloquent model to initialize the localized defaults.
     */
    public function initializeBelongsToSitesDefaults(): void
    {
        $this->mergeCasts(['locales' => 'array']);
    }

    public function getSiteLocales(): array
    {
        return $this->locales ?? [];
    }

    public function setSiteLocales(array $locales): void
    {
        $this->locales = $locales;
    }

    public function scopeBySite(Builder $query, string $site): void
    {
        $query->whereJsonContains($this->getTable().'.locales', $site);
    }

    public function scopeBySiteOrNone(Builder $query, string $site): void
    {
        $query->when($site, fn ($q) => $q->where(function ($q) use ($site) {
            $q->whereJsonContains($this->getTable().'.locales', $site)
                ->orWhereNull($this->getTable().'.locales')
                ->orWhereJsonLength($this->getTable().'.locales', '=', 0);
        }));
    }
}
