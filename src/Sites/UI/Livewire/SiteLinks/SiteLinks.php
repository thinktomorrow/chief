<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks;

use Livewire\Component;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\HasSiteLocales;

class SiteLinks extends Component
{
    use WithSiteLinks;

    public string $modelReference;

    public function mount(Visitable&HasSiteLocales&ReferableModel $model)
    {
        $this->modelReference = $model->modelReference()->get();
    }

    public function getListeners()
    {
        return [
            'site-links-updated' => 'onSiteLinksUpdated',
            'model-state-updated' => 'onSiteLinksUpdated',
        ];
    }

    public function edit(): void
    {
        $this->dispatch('open-edit-site-links')->to('chief-wire::edit-site-links');
    }

    public function onSiteLinksUpdated(): void
    {
        // The links are automatically updated in the view
        // because the getSiteLinks method is called again.
    }

    public function render()
    {
        return view('chief-sites::site-links.site-links');
    }
}
