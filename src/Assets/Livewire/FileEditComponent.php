<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;
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

    public function mount(string $parentId)
    {
        $this->parentId = $parentId;
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-' . $this->parentId => 'open',
            'externalAssetUpdated-'.$this->id => 'onExternalAssetUpdated',
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

    public function openFilesChooseExternal()
    {
        $this->emitDownTo('chief-wire::file-field-choose-external', 'open', ['assetId' => $this->previewFile->mediaId]);
    }

    public function updateExternalAsset()
    {
        $driver = app(DriverFactory::class)->create($this->previewFile->getData('external.type'));

        $driver->updateAsset(Asset::find($this->previewFile->mediaId), $this->previewFile->getData('external.id'));

        // Update previewfile to reflect the external asset data
        $this->previewFile = PreviewFile::fromAsset(Asset::find($this->previewFile->mediaId));

        $this->emitUp('assetUpdated', $this->previewFile);

        $this->close();
    }

    public function onExternalAssetUpdated()
    {
        // Update previewfile to reflect the external asset data
        $this->previewFile = PreviewFile::fromAsset(Asset::find($this->previewFile->mediaId));

        $this->emitUp('assetUpdated', $this->previewFile);

        $this->close();
    }

    public function openImageCrop()
    {
        $this->emitToSibling('chief-wire::image-crop', 'open', ['previewfile' => $this->previewFile]);
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

        $this->validateForm();

        if($this->previewFile->mediaId) {
            app(FileApplication::class)->updateFileName($this->previewFile->mediaId, $this->form['basename']);
        }

        // Update form values
        $this->syncForm();

        $this->emitUp('assetUpdated', $this->previewFile);

        $this->close();
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateForm(): void
    {
        $this->validate([
            'form.basename' => ['required','min:1','max:200'],
        ], [], [
            'form.basename' => 'bestandsnaam',
        ]);
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
