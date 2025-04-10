<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\DeleteContext;
use Thinktomorrow\Chief\Fragments\App\ContextActions\UpdateContext;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasSiteContexts;

class EditContext extends Component
{
    use HasForm;
    use ShowsAsDialog;

    public string $modelReference;

    public ContextDto $context;

    public bool $cannotBeDeleted = false;

    public bool $cannotBeDeletedBecauseOfLastLeft = false;

    public bool $cannotBeDeletedBecauseOfConnectedToSite = false;

    public array $modelLocales;

    public function mount(string $modelReference, array $modelLocales)
    {
        $this->modelReference = $modelReference;

        $this->modelLocales = $modelLocales;
    }

    public function getListeners()
    {
        return [
            'open-edit-context' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->context = app(ComposeLivewireDto::class)->getContext(ModelReference::fromString($this->modelReference), $values['contextId']);

        $this->setDeletionFlags();

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        $this->isOpen = true;
    }

    private function initialFormValues(): void
    {
        $this->form = [
            'title' => $this->context->title,
            'locales' => $this->context->locales,
            'active_sites' => $this->context->activeSites,
        ];
    }

    public function close()
    {
        $this->reset(['form', 'context', 'cannotBeDeleted', 'cannotBeDeletedBecauseOfLastLeft', 'cannotBeDeletedBecauseOfConnectedToSite']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function deleteContext(): void
    {
        app(ContextApplication::class)->safeDelete(new DeleteContext(
            $this->context->id,
        ));

        $this->dispatch($this->modelReference.'-context-deleted', ['contextId' => $this->context->id]);

        $this->close();
    }

    public function save()
    {
        $this->validate([
            'form.locales' => ['required', 'array', 'min:1'],
            'form.title' => 'required',
        ], [
            'form.locales.required' => 'Duid minstens één site aan. Dit bepaalt in welke talen je de fragmenten kan invullen.',
            'form.title.required' => 'Voorzie nog voor jezelf een titel. Kort en bondig.',
        ]);

        // Active sites can only consist of the locales that are selected
        $this->form['active_sites'] = array_values(array_intersect($this->form['locales'], $this->form['active_sites']));

        app(ContextApplication::class)->update(new UpdateContext(
            $this->context->id,
            $this->form['locales'] ?? [],
            $this->form['active_sites'] ?? [],
            $this->form['title'] ?? null
        ));

        $this->dispatch($this->modelReference.'-contexts-updated', ...[
            'contextId' => $this->context->id,
        ]);

        $this->close();
    }

    public function render()
    {
        return view('chief-fragments::livewire.edit-context');
    }

    public function getAvailableLocales(): array
    {
        return ChiefSites::all()->filterByLocales($this->modelLocales)->toCollection()->pluck('shortName', 'locale')->all();
    }

    private function setDeletionFlags(): void
    {
        $contexts = app(ContextRepository::class)->getByOwner($this->context->ownerReference);

        if ($contexts->count() < 2) {
            $this->cannotBeDeletedBecauseOfLastLeft = true;
            $this->cannotBeDeleted = true;
        }

        if (count($this->context->activeSites) > 0) {
            $this->cannotBeDeletedBecauseOfConnectedToSite = true;
            $this->cannotBeDeleted = true;
        }

        $model = ModelReference::fromString($this->modelReference)->instance();

        if ($model instanceof HasSiteContexts && $model->hasSiteContext($this->context->id)) {
            $this->cannotBeDeletedBecauseOfConnectedToSite = true;
            $this->cannotBeDeleted = true;
        }
    }
}
