<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteDto;

class FragmentSiteToggle extends Component
{
    public Collection $sites;

    public array $locales;

    public string $scopedLocale;

    public function mount(array $locales, string $scopedLocale)
    {
        $this->locales = $locales;

        $this->scopedLocale = $scopedLocale;

        $this->sites = $this->getSites();
    }

    public function updatedScopedLocale()
    {
        $this->dispatch('fragment-scoped-to-locale', ...['locale' => $this->scopedLocale]);
    }

    public function getListeners()
    {
        return [

        ];
    }

    public function render()
    {
        return view('chief-sites::site-toggle');
    }

    private function getSites(): Collection
    {
        return ChiefSites::all()
            ->filterByLocales($this->locales)
            ->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
