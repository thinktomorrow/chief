<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteContexts;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\Sites\SiteDto;

trait WithSites
{
    private function getSites(): Collection
    {
        return ChiefSites::all()->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
