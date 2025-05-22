<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteDto;

class SiteSelection extends Component
{
    public ReferableModel $model;

    public function mount(ReferableModel $model)
    {
        $this->model = $model;
    }

    public function getListeners()
    {
        return [
            'allowed-sites-updated' => 'onSitesUpdated',
        ];
    }

    public function edit(): void
    {
        $this->dispatch('open-edit-site-selection')->to('chief-wire::edit-site-selection');
    }

    public function onSitesUpdated(): void
    {
        // The links are automatically updated in the view
        // because the getSiteLinks method is called again.
    }

    public function render()
    {
        return view('chief-sites::site-selection.site-selection');
    }

    public function isAllowedToSelectSites(): bool
    {
        return $this->model instanceof HasAllowedSites && $this->model->allowSiteSelection();
    }

    public function getSites(): Collection
    {
        $model = $this->model;

        $locales = $model instanceof HasAllowedSites ? $model->getAllowedSites() : ChiefSites::locales();

        return ChiefSites::all()->filterByLocales($locales)->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
