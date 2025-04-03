<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteContexts;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Fragments\UI\Livewire\ContextDto;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\Actions\SaveSiteContexts;
use Thinktomorrow\Chief\Sites\Actions\SaveSiteLocales;
use Thinktomorrow\Chief\Sites\HasSiteLocales;

class EditSiteContexts extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithActiveContext;
    use WithSites;

    public string $modelReference;

    /** @var Collection<ContextDto> */
    public Collection $contexts;

    public function mount(string $modelReference)
    {
        $this->modelReference = $modelReference;
    }

    public function getListeners()
    {
        return [
            'open-edit-site-contexts' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->refreshContexts();

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
        $this->validate([
            'form.*.context' => 'required',
        ]);

        $model = ModelReference::fromString($this->modelReference)->instance();

        $locales = collect($this->form)->reject(fn ($values) => ! $values)->keys()->toArray();

        if ($model instanceof HasSiteLocales) {
            app(SaveSiteLocales::class)->handle($model, $locales);
        }

        $contextsByLocale = collect($this->form)->map(fn ($values) => $values['context'])->all();

        app(SaveSiteContexts::class)->handle($model, $contextsByLocale);

        $this->dispatch('site-contexts-updated')
            ->to('chief-wire::site-contexts');

        $this->close();
    }

    public function render()
    {
        return view('chief-sites::site-contexts.edit-site-contexts');
    }

    public function queuedForDeletion(string $locale): bool
    {
        return ! isset($this->form[$locale]) || ! $this->form[$locale];
    }

    private function initialFormValues()
    {
        foreach ($this->getSites() as $site) {

            // Keep existing form values, only add new ones
            if (isset($this->form[$site->locale])) {
                continue;
            }

            $this->form[$site->locale] = [
                'context' => ($this->findActiveContext($site->locale) ? $this->findActiveContext($site->locale)->id : null),
            ];
        }
    }
}
