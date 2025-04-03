<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;

trait HasSiteLocalesDefaults
{
    /**
     * This method is called on an Eloquent model to initialize the localized defaults.
     */
    public function initializeHasSiteLocalesDefaults(): void
    {
        $this->mergeCasts(['locales' => 'array']);
    }

    public function getSiteLocales(): array
    {
        return ($this->locales && ! empty($this->locales)) ? $this->locales : ChiefLocales::locales();
    }

    public function setSiteLocales(array $locales): void
    {
        $this->locales = $locales;
    }

    public function scopeBySiteLocale(Builder $query, string $site): void
    {
        $query->whereJsonContains($this->getTable().'.locales', $site);
    }

    public function scopeBySiteLocaleOrNone(Builder $query, string $site): void
    {
        $query->when($site, fn ($q) => $q->where(function ($q) use ($site) {
            $q->whereJsonContains($this->getTable().'.locales', $site)
                ->orWhereNull($this->getTable().'.locales')
                ->orWhereJsonLength($this->getTable().'.locales', '=', 0);
        }));
    }
}
