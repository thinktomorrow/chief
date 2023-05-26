<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Forms\Livewire\LivewireAssist;

class FileEditComponent extends Component
{
    use ShowsAsDialog;
    use WithFileUploads;

    public $parentId;
    public string $modelReference;
    public string $fieldKey;
    public string $locale;

    public ?PreviewFile $previewFile = null;
    public ?MediaFile $mediaFile = null;
    public $form = [];
    public $components = [];

    public function mount(string $modelReference, string $fieldKey, string $locale, string $parentId, array $components = [])
    {
        $this->modelReference = $modelReference;
        $this->fieldKey = $fieldKey;
        $this->locale = $locale;
        $this->parentId = $parentId;

        $this->components = array_map(fn ($component) => $component->toLivewire(), $components);
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-' . $this->parentId => 'open',
        ];
    }

    public function getComponents(): array
    {
        return array_map(function ($componentArray) {
            $component = $componentArray['class']::fromLivewire($componentArray);
            //            $component->key(LivewireAssist::formDataIdentifier($component->getKey()));
            return $component;
        }, $this->components);
    }

    private function setFile(PreviewFile $previewFile)
    {
        $this->previewFile = $previewFile;
        $this->form['basename'] = $previewFile->getBaseName();

        if($previewFile->mediaId) {
            $mediaModel = Media::find($previewFile->mediaId);
            $this->mediaFile = MediaFile::fromMedia($mediaModel);
        }

        $this->setFieldValues();
    }

    private function setFieldValues()
    {
        foreach($this->components as $componentArray) {
            $component = $componentArray['class']::fromLivewire($componentArray);

            if(! $component instanceof Field) {
                continue;
            }

            Arr::set(
                $this->form,
                $component->getKey(),
                data_get($this->previewFile->fieldValues, $component->getKey())
            );
        }
    }

    public function open($value)
    {
        $this->setFile(is_array($value['previewfile']) ? PreviewFile::fromArray($value['previewfile']) : $value['previewfile']);
        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['previewFile','mediaFile','form']);
        $this->isOpen = false;
    }

//    public function updatedFiles(): void
//    {
//        $currentCount = count($this->previewFiles);
//
//        $this->syncPreviewFiles();
//
//        if(count($this->previewFiles) > $currentCount) {
//            $this->emit('fileAdded');
//        }
//    }

//    public function rules(): array
//    {
////        $rules = []
//        // Rename to validationParameters
//        $validationParameters = ValidationParameters::make($field);
//
//        return $this->validatorFactory->make(
//            $payload,
//            $validationParameters->getRules(),
//            $validationParameters->getMessages(),
//            $validationParameters->getAttributes(),
//        );
//
//
//        return ['form.url' => 'required|numeric'];
//    }
//
//    public function messages(): array
//    {
//
//    }
//
//    public function validationAttributes(): array
//    {
//
//    }

    public function openImageCrop()
    {
        $this->emitToSibling('chief-wire::image-crop', 'openInParentScope', ['previewfile_array' => $this->previewFile]);
    }

    public function submit()
    {
        $this->validateForm();

        if($this->mediaFile) {
            app(FileApplication::class)->updateFileName($this->mediaFile->mediaId, $this->form['basename']);
            app(FileApplication::class)->updateFieldValues($this->modelReference, $this->fieldKey, $this->locale, $this->mediaFile->mediaId, $this->form);
        }

        $this->emitUp('assetUpdated', $this->previewFile->id, $this->form);

        $this->close();
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateForm(): void
    {
        $rules = $messages = $validationAttributes = [];

        foreach ($this->getComponents() as $component) {
            if ($component instanceof Field) {

                $component->name(FormKey::replaceDotsByBrackets(LivewireAssist::formDataIdentifier($component->getName())));

                $validationParameters = ValidationParameters::make($component);
                $rules = array_merge($rules, $validationParameters->getRules());
                $messages = array_merge($messages, $validationParameters->getMessages());
                $validationAttributes = array_merge($validationAttributes, $validationParameters->getAttributes());
            }
        }

        $this->validate($rules, $messages, $validationAttributes);
    }

    public function render()
    {
        return view('chief-assets::file-edit', [
            //
        ]);
    }

    private function emitToSibling($name, $event, array $params)
    {
        $params['parent_id'] = $this->parentId;
        $this->emitTo($name, $event, $params);
    }
}
