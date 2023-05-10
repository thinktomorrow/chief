<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\Chief\Forms\Fields\File\App\FileApplication;

class ImageCropComponent extends Component
{
    use WithFileUploads;

    public $isOpen = false;

    public ?PreviewFile $previewFile = null;
    public ?MediaFile $mediaFile = null;
    public $formValues = [];
    public $parentId;

    public $listeners = [
        'openInParentScope' => 'openInParentScope',
        'open' => 'open',
        'imageCropped' => 'updateCropSelection',
    ];

    public function mount(string $parentId)
    {
        $this->parentId = $parentId;
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

        $this->emit('imageCropOpened', $this->previewFile->id);
    }

    public function close()
    {
        $this->reset(['previewFile','mediaFile','formValues']);
        $this->isOpen = false;
    }

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

    public function updateCropSelection($value)
    {
        $x = $value['x'];
        $y = $value['y'];
        $width = $value['width'];
        $height = $value['height'];


    }

    public function submit()
    {
        // REPLACE Save new file under the same media id...

        // for new file: perform upload and then emitUp with newpath, name and mimeType...
        dd('submitting');
        if($this->mediaFile) {
            app(FileApplication::class)->updateFileName($this->mediaFile->mediaId, $this->formValues['basename']);
        }

        $this->emitUp('fileUpdated', $this->previewFile->id, $this->formValues);

        $this->close();
    }

    public function render()
    {
        return view('chief-form::fields.file.image-crop', [
            //
        ]);
    }
}
