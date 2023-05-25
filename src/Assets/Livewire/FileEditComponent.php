<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Fields\Field;

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
    public $formValues = [];
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
        return array_map(fn($componentArray) => $componentArray['class']::fromLivewire($componentArray), $this->components);
    }

    private function setFile(PreviewFile $previewFile)
    {
        $this->previewFile = $previewFile;
        $this->formValues['basename'] = $previewFile->getBaseName();

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

            if(!$component instanceof Field) continue;

            Arr::set($this->formValues,
                $component->getKey(),
                data_get($this->previewFile->fieldValues,$component->getKey())
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
        $this->reset(['previewFile','mediaFile','formValues']);
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
//
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
        // Values should be:
        // - Validate
        // - Save to Asset as custom properties
        if($this->mediaFile) {
            app(FileApplication::class)->updateFileName($this->mediaFile->mediaId, $this->formValues['basename']);
            app(FileApplication::class)->updateFieldValues($this->modelReference, $this->fieldKey, $this->locale, $this->mediaFile->mediaId, $this->formValues);
        }

        $this->emitUp('assetUpdated', $this->previewFile->id, $this->formValues);

        $this->close();
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
