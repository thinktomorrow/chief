<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
        return ($this->locales && ! empty($this->locales)) ? $this->locales : ChiefSites::locales();
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

        if (DB::getDriverName() === 'sqlite') {
            $query->orderByRaw("
            CASE
                WHEN locales IS NULL OR locales = '[]' THEN 1
                ELSE 0
            END
        ");
        } else {
            $query->orderByRaw('
            CASE
                WHEN JSON_TYPE(locales) IS NULL OR JSON_LENGTH(locales) = 0 THEN 1
                ELSE 0
            END
        ');
        }
    }
}
