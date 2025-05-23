<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Admin\Users\LocaleScope;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteDto;

class GlobalSiteToggle extends Component
{
    public Collection $sites;

    public string $scopedLocale;

    public function mount()
    {
        $this->sites = $this->getSites();

        $defaultLocaleScope = ChiefSites::getLocaleScope();

        $this->scopedLocale = (request()->input('site') && ChiefSites::verify(request()->input('site')))
            ? request()->input('site')
            : ($this->sites->isEmpty() || $this->sites->contains(fn ($site) => $site->locale == $defaultLocaleScope) ? $defaultLocaleScope : $this->sites->first()->locale);
    }

    public function getListeners()
    {
        return [
            'allowed-sites-updated' => 'onSiteLinksUpdated',
            'model-scoped-to-locale' => 'onScopedToLocale',
        ];
    }

    public function updatedScopedLocale()
    {
        app(LocaleScope::class)->set($this->scopedLocale);

        $this->dispatch('scoped-to-locale', ...['locale' => $this->scopedLocale]);
        $this->dispatch('global-scoped-to-locale', ...['locale' => $this->scopedLocale]);
    }

    public function onScopedToLocale($locale)
    {
        $this->scopedLocale = $locale;
    }

    public function render()
    {
        return view('chief-sites::global-site-toggle');
    }

    private function getSites(): Collection
    {
        return ChiefSites::all()
            ->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
