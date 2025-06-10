<?php

namespace Thinktomorrow\Chief\Plugins\Seo\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Forms\UI\Livewire\InteractsWithFields;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\UI\Livewire\WithLocaleToggle;

class EditAltComponent extends Component
{
    use HasForm;
    use InteractsWithFields;
    use ShowsAsDialog;
    use WithLocaleToggle;

    public ModelReference $modelReference;

    public function mount() {}

    public function getListeners()
    {
        return [
            'open-edit-alt' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->isOpen = true;

        $this->modelReference = ModelReference::fromString($values['modelReference']);

        $components = $this->getComponents();

        $this->setLocalesOnOpen($values, $components);

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->injectFormValues($components);

        $this->dispatch('form-dialog-opened', ...[
            'componentId' => $this->getId(),
        ]);
    }

    public function close()
    {
        $this->reset(['modelReference', 'form', 'locales', 'scopedLocale']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function getComponents(): array
    {
        $asset = $this->modelReference->instance();

        // We assume we have only one form in this component, so we can use the first one.
        return [Layout::make($asset->fields($asset))
            ->model($asset)
            ->getFields()
            ->find('alt')];
    }

    public function save()
    {
        $form = $this->prepareFormDataForSubmission();

        app(FileApplication::class)->updateAssetData($this->modelReference->id(), $form);

        $this->close();

        // Refreshes the table
        $this->dispatch('requestRefresh');
    }

    public function render()
    {
        return view('chief-seo::alt.edit-alt', [
            'form' => $this->form,
            'locales' => $this->locales,
            'scopedLocale' => $this->scopedLocale,
        ]);
    }
}
