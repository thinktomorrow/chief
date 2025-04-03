<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\Sites;

use Livewire\Component;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\HasSiteLocales;

class Sites extends Component
{
    use WithSites;

    public string $modelReference;

    public function mount(HasSiteLocales&ReferableModel $model)
    {
        $this->modelReference = $model->modelReference()->get();
    }

    public function getListeners()
    {
        return [
            'sites-updated' => 'onSitesUpdated',
        ];
    }

    public function edit(): void
    {
        $this->dispatch('open-edit-sites')->to('chief-wire::edit-sites');
    }

    public function onSitesUpdated(): void
    {
        // The links are automatically updated in the view
        // because the getSiteLinks method is called again.
    }

    public function render()
    {
        return view('chief-sites::sites.sites');
    }
}
