<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasSiteLocales;
use Thinktomorrow\Chief\Sites\UI\Livewire\Sites\SiteDto;

class SiteTabs extends Component
{
    public ModelReference $modelReference;

    public Collection $sites;

    public function mount(HasSiteLocales&ReferableModel $model)
    {
        $this->modelReference = $model->modelReference();

        $this->sites = $this->getSites($model);
    }

    public function getListeners()
    {
        return [
            'site-links-updated' => 'onSiteLinksUpdated',
        ];
    }

    public function onSiteLinksUpdated(): void
    {
        // The links are automatically updated in the view
        // because the getSites method is called again.
        $this->sites = $this->getSites($this->modelReference->instance());
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
