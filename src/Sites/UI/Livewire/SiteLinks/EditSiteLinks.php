<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\Actions\SaveSiteLocales;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\DeleteUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UpdateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\ValidationRules\UniqueUrlSlugRule;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class EditSiteLinks extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithAddingSites;
    use WithSiteLinks;

    public string $modelReference;

    public Collection $sites;

    public array $deletionQueue = [];

    public array $redirectDeletionQueue = [];

    public function mount(string $modelReference)
    {
        $this->modelReference = $modelReference;
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
        $this->reset(['form', 'sites', 'addingLocales', 'deletionQueue', 'redirectDeletionQueue']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function saveAddingSites(): void
    {
        $addedSiteLinks = collect($this->addingLocales)->map(function ($locale) {
            return SiteLink::empty(ModelReference::fromString($this->modelReference)->instance(), $locale);
        });

        $this->sites = $this->sites->merge($addedSiteLinks);

        $this->initialFormValues();

        $this->closeAddingSites();
    }

    public function getLinkStatusOptions(): array
    {
        return LinkStatus::options();
    }

    public function save()
    {
        $model = ModelReference::fromString($this->modelReference)->instance();

        $this->validate([
            'form.*.slug' => ['required', new UniqueUrlSlugRule($model, $model)],
            'form.*.status' => 'required',
        ], [
            'form.*.slug.required' => 'Gelieve een link in te geven',
            'form.*.status.required' => 'Status is verplicht',
        ]);

        $locales = collect($this->form)
            ->reject(fn ($values) => ! $values)
            ->reject(fn ($value, $key) => in_array($key, $this->deletionQueue))
            ->keys()->toArray();

        app(SaveSiteLocales::class)->handle($model, $locales);

        foreach ($this->form as $locale => $values) {

            $siteLink = $this->sites->first(fn ($siteLink) => $siteLink->locale == $locale);
            $urlRecordExists = $siteLink->url && $siteLink->url->id;

            if ($urlRecordExists && in_array($locale, $this->deletionQueue)) {
                app(UrlApplication::class)->delete(new DeleteUrl($siteLink->url->id));

                continue;
            }

            if ($urlRecordExists) {
                app(UrlApplication::class)->update(new UpdateUrl($siteLink->url->id, $values['slug'], $values['status']));

                continue;
            }

            app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), $locale, $values['slug'], $values['status']));
        }

        // Handle removing redirects ...
        foreach ($this->redirectDeletionQueue as $recordId) {
            app(UrlApplication::class)->delete(new DeleteUrl($recordId));
        }

        $this->dispatch('site-links-updated');

        $this->close();
    }

    public function render()
    {
        return view('chief-sites::site-links.edit-site-links');
    }

    public function deleteSite(string $locale): void
    {
        $this->deletionQueue[] = $locale;
    }

    public function undoDeleteSite(string $locale): void
    {
        $this->deletionQueue = collect($this->deletionQueue)->reject(fn ($value) => $value == $locale)->toArray();
    }

    public function queuedForDeletion(string $locale): bool
    {
        return array_search($locale, $this->deletionQueue) !== false;
    }

    public function deleteRedirect(string $recordId): void
    {
        $this->redirectDeletionQueue[] = $recordId;
    }

    public function undoDeleteRedirect(string $recordId): void
    {
        $this->redirectDeletionQueue = collect($this->redirectDeletionQueue)->reject(fn ($value) => $value == $recordId)->toArray();
    }

    public function redirectQueuedForDeletion(string $recordId): bool
    {
        return array_search($recordId, $this->redirectDeletionQueue) !== false;
    }

    private function initialFormValues()
    {
        foreach ($this->sites as $siteLink) {

            // Keep existing form values, only add new ones
            if (isset($this->form[$siteLink->locale])) {
                continue;
            }

            $this->form[$siteLink->locale] = [
                'slug' => $siteLink->url?->slugWithoutBaseUrlSegment,
                'status' => $siteLink->status->value,
                'context' => $siteLink->contextId,
            ];
        }
    }
}
