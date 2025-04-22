<?php

namespace Thinktomorrow\Chief\Urls\UI\Livewire\Links;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithMemoizedModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\DeleteUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UpdateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\ValidationRules\UniqueUrlSlugRule;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class EditLinks extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithLinks;
    use WithMemoizedModel;

    public ModelReference $modelReference;

    public Collection $links;

    public array $deletionQueue = [];

    public array $redirectDeletionQueue = [];

    public function mount(ReferableModel $model)
    {
        $this->modelReference = $model->modelReference();
        $this->setMemoizedModel($model);
    }

    public function getListeners()
    {
        return [
            'open-edit-links' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->links = $this->getLinks();

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        // TODO: show for each site a link form entry..!!!!

        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['form', 'links', 'deletionQueue', 'redirectDeletionQueue']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function getLinkStatusOptions(): array
    {
        return LinkStatus::options();
    }

    public function save()
    {
        $model = $this->getModel();

        $this->validate([
            'form.*.slug' => ['required', new UniqueUrlSlugRule($model, $model)],
            'form.*.status' => 'required',
        ], [
            'form.*.slug.required' => 'Gelieve een link in te geven',
            'form.*.status.required' => 'Status is verplicht',
        ]);

        //        $locales = collect($this->form)
        //            ->reject(fn ($values) => ! $values)
        //            ->reject(fn ($value, $key) => in_array($key, $this->deletionQueue))
        //            ->keys()->toArray();

        foreach ($this->form as $locale => $values) {

            $link = $this->links->first(fn ($_link) => $_link->locale == $locale);
            $urlRecordExists = $link->url && $link->url->id;

            if ($urlRecordExists && in_array($locale, $this->deletionQueue)) {
                app(UrlApplication::class)->delete(new DeleteUrl($link->url->id));

                continue;
            }

            if ($urlRecordExists) {
                app(UrlApplication::class)->update(new UpdateUrl($link->url->id, $values['slug'], $values['status']));

                continue;
            }

            app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), $locale, $values['slug'], $values['status']));
        }

        // Handle removing redirects ...
        foreach ($this->redirectDeletionQueue as $recordId) {
            app(UrlApplication::class)->delete(new DeleteUrl($recordId));
        }

        $this->dispatch('links-updated');

        $this->close();
    }

    public function render()
    {
        return view('chief-urls::links.edit-links');
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
        foreach ($this->links as $link) {

            // Keep existing form values, only add new ones
            if (isset($this->form[$link->locale])) {
                continue;
            }

            $this->form[$link->locale] = [
                'slug' => $link->url?->slugWithoutBaseUrlSegment,
                'status' => $link->status->value,
            ];
        }
    }

    public function allowedSite(string $locale): bool
    {
        if (! $this->getModel() instanceof HasAllowedSites) {
            return true;
        }

        return in_array($locale, $this->getModel()->getAllowedSites());
    }
}
