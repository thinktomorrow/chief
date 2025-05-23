<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\Actions\SaveAllowedSites;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

class EditSiteSelection extends Component
{
    use HasForm;
    use ShowsAsDialog;

    public HasAllowedSites&ReferableModel $model;

    public function mount(HasAllowedSites&ReferableModel $model)
    {
        $this->model = $model;
    }

    public function getListeners()
    {
        return [
            'open-edit-site-selection' => 'open',
        ];
    }

    public function open($values = [])
    {
        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['form']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function save()
    {
        //        $model = $this->modelReference->instance();
        $model = $this->model;

        app(SaveAllowedSites::class)->handle($model, $this->form['locales']);

        $this->dispatch('allowed-sites-updated', ...['allowedSites' => $this->form['locales']]);

        $this->close();
    }

    public function render()
    {
        return view('chief-sites::site-selection.edit-site-selection');
    }

    private function initialFormValues()
    {
        $model = $this->model;

        $this->form['locales'] = ChiefSites::all()->filterByLocales($model->getAllowedSites())->getLocales();
    }

    public function getAvailableSites(): Collection
    {
        return ChiefSites::all()->toCollection();
    }
}
