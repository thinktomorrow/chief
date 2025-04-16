<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteDto;

class ModelSiteToggle extends Component
{
    public ModelReference $modelReference;

    public Collection $sites;

    public ?string $scopedLocale = null;

    public function mount(HasAllowedSites&ReferableModel $model)
    {
        $this->modelReference = $model->modelReference();

        $this->sites = $this->getSites($model);

        $this->scopedLocale = (request()->input('site') && ChiefSites::verify(request()->input('site')))
            ? request()->input('site')
            : $this->sites->first()?->locale;
    }

    public function getListeners()
    {
        return [
            'site-links-updated' => 'onSiteLinksUpdated',
            'allowed-sites-updated' => 'onSiteLinksUpdated',
            'global-scoped-to-locale' => 'onScopedToLocale',
        ];
    }

    public function updatedScopedLocale()
    {
        $this->dispatch('model-scoped-to-locale', ...['locale' => $this->scopedLocale]);
        $this->dispatch('scoped-to-locale', ...['locale' => $this->scopedLocale]);
    }

    public function onScopedToLocale($locale)
    {
        $this->scopedLocale = $locale;
    }

    public function onSiteLinksUpdated(): void
    {
        // The links are automatically updated in the view
        // because the getSites method is called again.
        $this->sites = $this->getSites($this->modelReference->instance());
    }

    public function render()
    {
        return view('chief-sites::site-toggle');
    }

    private function getSites(HasAllowedSites $model): Collection
    {
        return ChiefSites::all()
            ->filterByLocales($model->getAllowedSites())
            ->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
