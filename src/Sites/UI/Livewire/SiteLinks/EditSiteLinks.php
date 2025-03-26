<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\ContextDto;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\Application\CreateUrl;
use Thinktomorrow\Chief\Site\Urls\Application\DeleteUrl;
use Thinktomorrow\Chief\Site\Urls\Application\UpdateUrl;
use Thinktomorrow\Chief\Site\Urls\LinkStatus;
use Thinktomorrow\Chief\Sites\Actions\SaveModelSites;
use Thinktomorrow\Chief\Sites\UI\Livewire\Sites\WithAddingSites;

class EditSiteLinks extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithAddingSites;
    use WithSiteLinks;

    public string $modelReference;

    public Collection $sites;

    /** @var Collection<ContextDto> */
    public Collection $contexts;

    public function mount(string $modelReference)
    {
        $this->modelReference = $modelReference;
        $this->contexts = app(ComposeLivewireDto::class)->getContextsByOwner(ModelReference::fromString($modelReference));
    }

    public function getListeners()
    {
        return [
            'open-edit-site-links' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->sites = $this->getSiteLinks();

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        // Immediately show the add sites dialog if no sites are present
        if (count($this->sites) < 1) {
            $this->addSites();
        }

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
        $addedSiteLinks = collect($this->addingLocales)->map(function ($locale) {
            return SiteLink::empty($locale);
        });

        $this->sites = $this->sites->merge($addedSiteLinks);

        $this->initialFormValues();

        $this->closeAddingSites();
    }

    public function getLinkStatusOptions(): array
    {
        return LinkStatus::options();
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

        foreach ($this->form as $locale => $values) {

            $siteLink = $this->sites->first(fn ($siteLink) => $siteLink->locale == $locale);
            $urlRecordExists = $siteLink->url && $siteLink->url->id;

            if (! $values || ! $values['slug']) {

                if ($urlRecordExists) {
                    app(DeleteUrl::class)->handle($siteLink->url->id);
                }

                // If slug is empty and no url record exists, we can skip this one
                continue;
            }

            if ($urlRecordExists) {
                app(UpdateUrl::class)->handle(
                    $siteLink->url->id,
                    $values['slug'],
                    $values['context'],
                    LinkStatus::from($values['status'])
                );

                continue;
            }

            // Create
            app(CreateUrl::class)->handle(
                $model,
                $locale,
                $values['slug'],
                $values['context'],
                LinkStatus::from($values['status'])
            );
        }

        $this->dispatch('site-links-updated')
            ->to('chief-wire::site-links');

        $this->close();
    }

    public function render()
    {
        return view('chief-sites::site-links.edit-site-links');
    }

    public function queuedForDeletion(string $locale): bool
    {
        return ! isset($this->form[$locale]) || ! $this->form[$locale];
    }

    private function initialFormValues()
    {
        foreach ($this->sites as $siteLink) {

            // Keep existing form values, only add new ones
            if (isset($this->form[$siteLink->locale])) {
                continue;
            }

            $this->form[$siteLink->locale] = [
                'slug' => $siteLink->url?->slug,
                'status' => $siteLink->status->value,
                'context' => $siteLink->contextId,
            ];
        }
    }
}
