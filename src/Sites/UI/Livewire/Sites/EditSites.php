<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\Sites;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\Actions\SaveModelSites;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;

class EditSites extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithAddingSites;
    use WithSites;

    public string $modelReference;

    public Collection $sites;

    public function mount(string $modelReference)
    {
        $this->modelReference = $modelReference;
    }

    public function getListeners()
    {
        return [
            'open-edit-sites' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->sites = $this->getSites();

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['form', 'sites', 'addingLocales']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function saveAddingSites(): void
    {
        $addedSites = collect($this->addingLocales)->map(function ($locale) {
            return SiteDto::fromConfig(ChiefSites::all()->find($locale));
        });

        $this->sites = $this->sites->merge($addedSites);

        $this->initialFormValues();

        $this->closeAddingSites();
    }

    /** @return ChiefSite[] */
    public function getNonAddedSites(): array
    {
        $locales = $this->sites->map(fn ($site) => $site->locale)->toArray();

        return ChiefSites::all()->rejectByLocales($locales)->get();
    }

    public function deleteSite(string $locale): void
    {
        $form = $this->form;

        $this->form[$locale] = null;
    }

    public function undoDeleteSite(string $locale): void
    {
        $this->initialFormValues();
    }

    public function save()
    {
        //        $this->validate([
        //            'form.*.slug' => 'required',
        //            'form.*.status' => 'required',
        //        ]);

        $model = ModelReference::fromString($this->modelReference)->instance();

        $locales = collect($this->form)->reject(fn ($values) => ! $values)->keys()->toArray();

        app(SaveModelSites::class)->handle($model, $locales);

        $this->dispatch('sites-updated')
            ->to('chief-wire::sites');

        $this->close();
    }

    public function render()
    {
        return view('chief-sites::sites.edit-sites');
    }

    public function queuedForDeletion(string $locale): bool
    {
        return ! isset($this->form[$locale]) || ! $this->form[$locale];
    }

    private function initialFormValues()
    {
        foreach ($this->sites as $site) {
            $this->form[$site->locale] = $site->locale;
        }
    }
}
