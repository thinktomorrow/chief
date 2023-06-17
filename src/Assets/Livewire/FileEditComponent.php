<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class FileEditComponent extends Component
{
    use ShowsAsDialog;
    use WithFileUploads;

    public $parentId;

    public ?PreviewFile $previewFile = null;
    public ?PreviewFile $replacedPreviewFile = null;
    public $form = [];
    public $file = null;
    public bool $showReplaceActions = false;

    public function mount(string $parentId)
    {
        $this->parentId = $parentId;
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-' . $this->parentId => 'open',
        ];
    }

    public function open($value)
    {
        $this->setFile(is_array($value['previewfile']) ? PreviewFile::fromArray($value['previewfile']) : $value['previewfile']);
        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['previewFile','form']);
        $this->isOpen = false;
    }

    private function setFile(PreviewFile $previewFile)
    {
        $this->previewFile = $previewFile;
        $this->extractFormFromPreviewFile();
    }

    public function getComponents(): array
    {
        return [];
    }

    public function updatedFile(): void
    {
        if(! $this->replacedPreviewFile) {
            $this->replacedPreviewFile = $this->previewFile;
        }
        $this->previewFile = PreviewFile::fromTemporaryUploadedFile($this->file);
        $this->syncForm();
    }

    private function extractFormFromPreviewFile()
    {
        $this->form['basename'] = $this->previewFile->getBaseName();
    }

    private function syncForm()
    {
        $this->previewFile->fieldValues = $this->form;

        $this->form['basename'] = $this->previewFile->getBaseName();
    }

    public function openImageCrop()
    {
        $this->emitDownTo('chief-wire::image-crop', 'open', ['previewfile' => $this->previewFile]);
    }

    public function submit()
    {
        if($this->replacedPreviewFile) {
            if($this->replacedPreviewFile->mediaId) {
                app(FileApplication::class)->replaceMedia($this->replacedPreviewFile->mediaId, $this->previewFile->toUploadedFile());
                $this->previewFile = PreviewFile::fromAsset(Asset::find($this->replacedPreviewFile->mediaId));
            } else {
                $this->previewFile->id = $this->replacedPreviewFile->id;
            }
        }

        if($this->previewFile->mediaId) {
            app(FileApplication::class)->updateFileName($this->previewFile->mediaId, $this->form['basename']);
        }

        // Update form values
        $this->syncForm();

        $this->emitUp('assetUpdated', $this->previewFile);

        $this->close();
    }

    public function render()
    {
        return view('chief-assets::file-edit', [
            //
        ]);
    }

    private function emitDownTo($name, $event, array $params = [])
    {
        $this->emitTo($name, $event . '-' . $this->id, $params);
    }

    private function emitToSibling($name, $event, array $params = [])
    {
        $this->emitTo($name, $event . '-' . $this->parentId, $params);
    }
}
