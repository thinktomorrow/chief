<?php

namespace Thinktomorrow\Chief\Urls\UI\Livewire\Links;

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
        $locales = $this->links->map(fn ($site) => $site->locale)->toArray();

        return ChiefSites::all()->rejectByLocales($locales)->get();
    }
}
