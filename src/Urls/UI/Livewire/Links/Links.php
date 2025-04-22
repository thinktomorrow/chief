<?php

namespace Thinktomorrow\Chief\Urls\UI\Livewire\Links;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithMemoizedModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class Links extends Component
{
    use WithLinks;
    use WithMemoizedModel;

    public ModelReference $modelReference;

    public function mount(Visitable&ReferableModel $model)
    {
        $this->modelReference = $model->modelReference();

        $this->setMemoizedModel($model);
    }

    public function getListeners()
    {
        return [
            'links-updated' => 'onLinksUpdated',
            'model-state-updated' => 'onLinksUpdated',
            'allowed-sites-updated' => 'onAllowedSitesUpdated',
        ];
    }

    public function edit(): void
    {
        $this->dispatch('open-edit-links')->to('chief-wire::edit-links');
    }

    public function onLinksUpdated(): void
    {
        // The links are automatically updated in the view
        // because the getSiteLinks method is called again.
    }

    public function onAllowedSitesUpdated(array $allowedSites): void
    {
        // Put all links offline that are not in the allowed sites
        $this->getLinks()->each(function ($link) use ($allowedSites) {
            if (! in_array($link->locale, $allowedSites)) {
                if ($link->url?->id) {
                    $record = UrlRecord::find($link->url->id);
                    $record->changeStatus(LinkStatus::offline);
                    $record->save();
                }
            }
        });

        // Fresh urls relation to reflect the status changes
        $this->getModel()->refresh();
    }

    public function render()
    {
        return view('chief-urls::links.links');
    }
}
