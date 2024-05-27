<?php

namespace Thinktomorrow\Chief\Sites;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

trait MultiSiteableDefault
{
    public function getLocales(): array
    {
        return $this->locales ?: [];
    }

    public function saveLocales(array $locales): void
    {
        $this->locales = $locales;

        $this->save();
    }

    protected function initializeMultiSiteableDefault()
    {
        $this->mergeCasts(['locales' => 'array']);
    }

    public function scopeByLocale(Builder $query): void
    {
        $query->whereIn($this->getTable().'.'.$this->getStateAttribute(), $this->onlineStates());
    }
}
