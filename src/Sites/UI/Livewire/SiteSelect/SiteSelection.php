<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteDto;

class SiteSelection extends Component
{
    public string $modelReference;

    public function mount(HasAllowedSites&ReferableModel $model)
    {
        $this->modelReference = $model->modelReference()->get();
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

    public function getSites(): Collection
    {
        $model = ModelReference::fromString($this->modelReference)->instance();

        return ChiefSites::all()->filterByLocales($model->getAllowedSites())->toCollection()
            ->map(fn (ChiefSite $site) => SiteDto::fromConfig($site));
    }
}
