<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks;

use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;

trait WithAddingSites
{
    public bool $addingSites = false;

    public array $addingLocales = [];

    public function addSites(): void
    {
        $this->addingSites = true;
    }

    public function closeAddingSites(): void
    {
        $this->addingSites = false;

        $this->reset('addingLocales');
    }

    /** @return ChiefSite[] */
    public function getNonAddedSites(): array
    {
        $locales = $this->sites->map(fn ($site) => $site->locale)->toArray();

        return ChiefSites::all()->rejectByLocales($locales)->get();
    }
}
