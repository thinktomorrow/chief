<?php

namespace Thinktomorrow\Chief\Models\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Forms\UI\Livewire\InteractsWithFields;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Models\App\Actions\CreateModel;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

class CreateModelComponent extends Component
{
    use HasForm;
    use InteractsWithFields;
    use ShowsAsDialog;

    public ?string $parentComponentId = null;

    public string $modelClass;

    public array $allowed_sites = [];

    public ?string $scopedLocale = null;

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

        $this->isOpen = true;

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->injectFormValues($this->getFields());

        $this->dispatch('form-dialog-opened', ...[
            'componentId' => $this->getId(),
        ]);
    }

    public function updatedAllowedSites(): void
    {
        //        if (count($this->allowed_sites) == 1) {
        //            $this->scopedLocale = $this->allowed_sites[0];
        //        }

        if (! in_array($this->scopedLocale, $this->allowed_sites)) {
            $this->scopedLocale = $this->allowed_sites[0] ?? null;
        }
    }

    public function close()
    {
        $this->reset(['form', 'modelClass', 'allowed_sites', 'scopedLocale']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function getFields(): Collection
    {
        $resource = $this->getResource();
        $model = new $this->modelClass($resource->getAttributesOnCreate());

        $layout = Layout::make($resource->fields($model))
            ->filterByNotTagged(['edit', 'not-on-model-create', 'not-on-create']) // TODO: make consistent tags...
            ->setScopedLocales($this->scopedLocale ? [$this->scopedLocale] : []);

        return $layout->getComponentsWithoutForms();
    }

    public function save()
    {
        if ($this->shouldShowAllowedSites()) {
            $this->validate([
                'allowed_sites' => ['required', 'array', 'min:1'],
            ], [
                'allowed_sites.required' => 'Duid minstens één site aan.',
            ]);
        }

        $modelId = app(ModelApplication::class)->create(new CreateModel(
            $this->modelClass,
            $this->allowed_sites,
            $this->form,
            [],
        ));

        return redirect()->to('/admin/'.$this->getResource()::resourceKey().'/'.$modelId.'/edit');
    }

    public function getTitle(): string
    {
        return 'Nieuwe '.$this->getResource()->getLabel().' aanmaken';
    }

    public function shouldShowAllowedSites(): bool
    {
        return (new \ReflectionClass($this->modelClass))->implementsInterface(HasAllowedSites::class);
    }

    public function render()
    {
        return view('chief-models::livewire.create-model');
    }

    private function getResource()
    {
        return app(Registry::class)->findResourceByModel($this->modelClass);
    }
}
