<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasSiteLocales;
use Thinktomorrow\Chief\Sites\UI\Livewire\Sites\SiteDto;

class SiteTabs extends Component
{
    public Collection $sites;

    public function mount(HasSiteLocales&ReferableModel $model)
    {
        $this->sites = $this->getSites($model);
    }

    public function render()
    {
        return view('chief-sites::site-tabs');
    }

    private function getSites(HasSiteLocales $model): Collection
    {
        return ChiefSites::all()
            ->filterByLocales($model->getSiteLocales())
            ->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
