<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;

trait MultiSiteableDefault
{
    public function getSiteLocales(): array
    {
        return $this->locales ?: [];
    }

    public function saveSiteLocales(array $locales): void
    {
        $this->locales = $locales;

        $this->save();
    }

    protected function initializeMultiSiteableDefault()
    {
        $this->mergeCasts(['locales' => 'array']);
    }

    public function scopeByLocale(Builder $query, string $locale): void
    {
        $query->whereJsonContains($this->getTable().'.locales', $locale);
    }

    public function scopeByLocaleOrNone(Builder $query, string $locale): void
    {
        $query->when($locale, fn ($q, $locale) => $q->where(function ($q) use ($locale) {
            $q->whereJsonContains($this->getTable().'.locales', $locale)
                ->orWhereNull($this->getTable().'.locales')
                ->orWhereJsonLength($this->getTable().'.locales', '=', 0);
        }));
    }
}
