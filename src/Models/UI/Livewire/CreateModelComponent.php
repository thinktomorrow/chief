<?php

namespace Thinktomorrow\Chief\Models\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Layouts\PageLayout;
use Thinktomorrow\Chief\Forms\UI\Livewire\InteractsWithFields;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Models\App\Actions\CreateModel;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Sites\UI\Livewire\WithLocaleToggle;
use Thinktomorrow\Chief\Table\Table;

class CreateModelComponent extends Component
{
    use HasForm;
    use InteractsWithFields;
    use ShowsAsDialog;
    use WithLocaleToggle;

    public ?string $parentComponentId = null;

    // Optional reference to a resource class as passed. This allows for any custom resource setup
    // where the model does not reference the resource directly (e.g. polymorphic relations).
    public ?string $resourceClass = null;

    public string $modelClass;

    public array $instanceAttributes = [];

    /** After save, redirect to the create model page */
    public bool $redirectAfterSave = true;

    public function mount() {}

    public function getListeners()
    {
        return [
            'open-create-model' => 'open',
            'files-updated' => 'onFilesUpdated',
        ];
    }

    public function open($values = [])
    {
        $this->modelClass = $values['modelClass'];
        $this->resourceClass = $values['resourceClass'] ?? null;
        $this->instanceAttributes = $values['instanceAttributes'] ?? [];
        $this->redirectAfterSave = $values['redirectAfterSave'] ?? true;

        $this->isOpen = true;

        $fields = $this->getFields();

        $this->initializeLocales(array_merge(['locales' => []], $values), $fields);

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->injectFormValues($fields);

        $this->dispatch('form-dialog-opened', ...[
            'componentId' => $this->getId(),
            'parentComponentId' => $this->parentComponentId,
        ]);
    }

    public function close()
    {
        $this->reset(['form', 'modelClass', 'locales', 'scopedLocale', 'redirectAfterSave']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function getFields(): Collection
    {
        $resource = $this->getResource();
        $model = new $this->modelClass($resource->getAttributesOnCreate($this->instanceAttributes));

        return PageLayout::make($resource->fields($model))
            ->filterByNotTagged(['edit', 'not-on-model-create', 'not-on-create']) // TODO: make consistent tags...
            ->model($model)
            ->getComponentsWithoutForms()
            ->pipe(fn ($components) => $this->applyFieldDependencies($components))
            ->reject(fn ($component) => $component instanceof Table);
    }

    public function save()
    {
        if ($this->shouldShowLocaleToggle()) {
            $this->validate([
                'locales' => ['required', 'array', 'min:1'],
            ], [
                'locales.required' => 'Duid minstens één site aan.',
            ]);
        }

        $form = $this->prepareFormDataForSubmission();

        $modelId = app(ModelApplication::class)->create(new CreateModel(
            $this->modelClass,
            $this->locales,
            $form,
            [],
        ));

        if ($this->redirectAfterSave) {
            return redirect()->to('/admin/'.$this->getResource()::resourceKey().'/'.$modelId.'/edit');
        }

        $this->close();

        $this->dispatch('requestRefresh-'.$this->parentComponentId);
    }

    public function getTitle(): string
    {
        return 'Nieuwe '.$this->getResource()->getLabel().' aanmaken';
    }

    public function render()
    {
        return view('chief-models::livewire.create-model');
    }

    private function getResource()
    {
        if (isset($this->resourceClass)) {
            return new $this->resourceClass;
        }

        return app(Registry::class)->findResourceByModel($this->modelClass);
    }
}
