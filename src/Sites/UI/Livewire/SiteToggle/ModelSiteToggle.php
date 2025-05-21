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

    public string $scopedLocale;

    public function mount(ReferableModel $model)
    {
        $this->modelReference = $model->modelReference();

        $this->sites = $this->getSites($model);

        $defaultLocaleScope = ChiefSites::getLocaleScope();

        $this->scopedLocale = (request()->input('site') && ChiefSites::verify(request()->input('site')))
            ? request()->input('site')
            : ($this->sites->isEmpty() || $this->sites->contains(fn ($site) => $site->locale == $defaultLocaleScope) ? $defaultLocaleScope : $this->sites->first()->locale);
    }

    public function getListeners()
    {
        return [
            'links-updated' => 'onSiteLinksUpdated',
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

    private function getSites($model): Collection
    {
        $locales = $model instanceof HasAllowedSites ? $model->getAllowedSites() : ChiefSites::locales();

        return ChiefSites::all()
            ->filterByLocales($locales)
            ->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
