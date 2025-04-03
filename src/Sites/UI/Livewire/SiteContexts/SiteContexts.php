<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteContexts;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\UI\Livewire\ContextDto;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\HasSiteContexts;

class SiteContexts extends Component
{
    use WithActiveContext;
    use WithSites;

    public string $modelReference;

    public array $activeContexts;

    /** @var Collection<ContextDto> */
    public Collection $contexts;

    public function mount(HasSiteContexts&ReferableModel&ContextOwner $model)
    {
        $this->modelReference = $model->modelReference()->get();
        $this->refreshContexts();
    }

    public function getListeners()
    {
        return [
            'site-contexts-updated' => 'onSiteContextsUpdated',
        ];
    }

    public function edit(): void
    {
        $this->dispatch('open-edit-site-contexts')->to('chief-wire::edit-site-contexts');
    }

    public function onSiteContextsUpdated(): void
    {
        $this->refreshContexts();
    }

    public function render()
    {
        return view('chief-sites::site-contexts.site-contexts');
    }
}
