<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\BelongsToSites;

class SiteTabs extends Component
{
    public string $modelReference;

    public array $sites = [];

    // Edit the site selection
    public bool $showSettings = false;

    public function mount(Visitable&BelongsToSites&ReferableModel $model)
    {
        $this->modelReference = $model->modelReference()->get();
        $this->sites = $model->getSiteIds();
    }

    public function render()
    {
        return view('chief-sites::site-tabs');
    }

    public function saveSettings(): void
    {
        // Save Site Ids to model
        $model = ModelReference::fromString($this->modelReference)->instance();
        $model->setSiteIds($this->sites);
        $model->save();

        $this->showSettings = false;
    }
}
