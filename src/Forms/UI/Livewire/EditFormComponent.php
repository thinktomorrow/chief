<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\App\Actions\UpdateForm;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class EditFormComponent extends Component
{
    use HasForm;
    use InteractsWithFields;
    use ShowsAsDialog;

    // parent livewire component id
    public string $parentComponentId;

    public ModelReference $modelReference;

    public Form $formComponent;

    public function mount(ModelReference $modelReference, \Thinktomorrow\Chief\Forms\Layouts\Form $formComponent, string $parentComponentId)
    {
        $this->modelReference = $modelReference;
        $this->formComponent = $formComponent;
        $this->parentComponentId = $parentComponentId;
    }

    public function getListeners()
    {
        return [
            'open-'.$this->parentComponentId => 'open',
            'files-updated' => 'onFilesUpdated', // Allow sync with file component
        ];
    }

    public function open($values = [])
    {
        $this->isOpen = true;

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->injectFormValues($this->getComponents());

        $this->dispatch('form-dialog-opened', ...[
            'componentId' => $this->getId(),
            'parentComponentId' => $this->parentComponentId,
            'formId' => $this->formComponent->getId(),
        ]);
    }

    // TODO(ben): this also closes parent dialogs
    public function close()
    {
        $this->reset(['form']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    /**
     * Compose the form again so we get all the closures
     * and such of all fields and layouts
     */
    public function getComponents(): array
    {
        $model = $this->modelReference->instance();
        $resource = app(Registry::class)->findResourceByModel($model::class);

        $layout = Layout::make($resource->fields($model));

        return $layout->findForm($this->formComponent->getId())
            ->model($model)
            ->getComponents();
    }

    public function save()
    {
        // TODO: validation
        app(UpdateForm::class)->handle(
            $this->modelReference,
            $this->formComponent->getId(),
            $this->prepareForSaving($this->form),
            [],
        );

        $this->dispatch('form-updated-'.$this->parentComponentId, ...[
            'formId' => $this->formComponent->getId(),
            'parentComponentId' => $this->parentComponentId,
        ]);

        $this->close();
    }

    public function render()
    {
        return view('chief-form::livewire.edit-form');
    }
}
