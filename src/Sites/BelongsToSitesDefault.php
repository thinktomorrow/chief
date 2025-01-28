<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToSitesDefault
{
    public function getSiteIds(): array
    {
        return $this->sites ?? [];
    }

    protected function initializeBelongsToSitesDefault()
    {
        $this->mergeCasts(['sites' => 'array']);
    }

    public function scopeBySite(Builder $query, string $site): void
    {
        // Site should be more of a ID. -> site as db table...
        // Locale is for fields and such... can be used by multiple sites.
        // Site is selection, choice. Locale is language, not WHAT is shown.
        // WHAT,HOW = site, language = locale
        $query->whereJsonContains($this->getTable().'.sites', $site);
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
