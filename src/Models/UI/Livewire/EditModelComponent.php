<?php

namespace Thinktomorrow\Chief\Models\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Layouts\PageLayout;
use Thinktomorrow\Chief\Forms\UI\Livewire\InteractsWithFields;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithMemoizedModel;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Models\App\Actions\UpdateModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\UI\Livewire\WithLocaleToggle;

class EditModelComponent extends Component
{
    use HasForm;
    use InteractsWithFields;
    use ShowsAsDialog;
    use WithLocaleToggle;
    use WithMemoizedModel {
        getModel as getMemoizedModel;
    }

    // parent livewire component id
    public string $parentComponentId;

    public ModelReference $modelReference;

    public function mount(string $parentComponentId)
    {
        $this->parentComponentId = $parentComponentId;
    }

    public function getListeners()
    {
        return [
            'open-edit-model' => 'open',
            'files-updated' => 'onFilesUpdated', // Allow sync with file component
        ];
    }

    public function open($values = [])
    {
        $this->modelReference = ModelReference::fromString($values['modelReference']);
        $this->setMemoizedModel($this->modelReference->instance());

        $this->isOpen = true;

        $components = $this->getComponents();

        $this->initializeLocales($values, $components);

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->injectFormValues($components);

        $this->dispatch('form-dialog-opened', ...[
            'componentId' => $this->getId(),
            'parentComponentId' => $this->parentComponentId,
        ]);
    }

    public function close()
    {
        $this->reset(['form', 'modelReference', 'locales', 'scopedLocale']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    /**
     * Compose the form again so we get all the closures
     * and such of all fields and layouts
     */
    public function getComponents(): Collection
    {
        return PageLayout::make($this->getResource()->fields($this->getModel()))
            ->filterByNotTagged(['create', 'not-on-model-edit', 'not-on-edit'])
            ->model($this->getModel())
            ->getComponentsWithoutForms()
            ->pipe(fn ($components) => $this->applyFieldDependencies($components));
    }

    public function save()
    {
        $form = $this->prepareFormDataForSubmission();

        app(ModelApplication::class)->updateModel(new UpdateModel(
            $this->modelReference,
            $this->locales, // Used to be scopedLocale but why???
            $form,
            [])
        );

        $this->dispatch('requestRefresh-'.$this->parentComponentId);

        $this->close();
    }

    public function getTitle(): string
    {
        return $this->getResource()->getPageTitle($this->getModel()).' bewerken';
    }

    public function render()
    {
        return view('chief-models::livewire.edit-model');
    }

    private function getResource()
    {
        return app(Registry::class)->findResourceByModel($this->modelReference->className());
    }

    public function getStateKeys(): array
    {
        if ($this->getModel() instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract) {
            return $this->getModel()->getStateKeys();
        }

        return [];
    }

    public function getModel()
    {
        return $this->getMemoizedModel();
    }
}
