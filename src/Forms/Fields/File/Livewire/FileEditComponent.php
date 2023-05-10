<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\Chief\Forms\Fields\File\App\FileApplication;

class FileEditComponent extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $parentId;

    public ?PreviewFile $previewFile = null;
    public ?MediaFile $mediaFile = null;
    public $formValues = [];
    public $components = [];

    public $listeners = [
        'open' => 'open',
        'openInParentScope' => 'openInParentScope',
    ];

    public function mount(string $parentId, array $components)
    {
        $this->parentId = $parentId;
        $this->components = array_map(fn ($component) => $component->toLivewire(), $components);
    }

//    public function booted()
//    {
//        $this->emitSelf('componentBooted');
//    }

    public function getComponents(): array
    {
        return array_map(fn ($componentArray) => $componentArray['class']::fromLivewire($componentArray), $this->components);
    }

    private function setFile(PreviewFile $previewFile)
    {
        $this->previewFile = $previewFile;
        $this->formValues['basename'] = $previewFile->getBaseName();

        if($previewFile->mediaId) {
            $mediaModel = Media::find($previewFile->mediaId);
            $this->mediaFile = MediaFile::fromMedia($mediaModel);
        }

    }

    public function openInParentScope($value)
    {
        if(! isset($value['parent_id']) || $this->parentId !== $value['parent_id']) {
            return;
        }

        $this->open($value);
    }

    public function open($value)
    {
        $this->setFile(PreviewFile::fromArray($value['previewfile_array']));
        $this->isOpen = true;

        $this->emitSelf('componentOpened');
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
        dd($this->formValues);

        // Values should be:
        // - Validate
        // - Save to Asset as custom properties

        if($this->mediaFile) {
            app(FileApplication::class)->updateFileName($this->mediaFile->mediaId, $this->formValues['basename']);
        }

        $this->emitUp('fileUpdated', $this->previewFile->id, $this->formValues);

        $this->close();
    }

    public function render()
    {
        return view('chief-form::fields.file.file-edit', [
            //
        ]);
    }

    private function emitToSibling($name, $event, array $params)
    {
        $params['parent_id'] = $this->parentId;
        $this->emitTo($name, $event, $params);
    }
}
