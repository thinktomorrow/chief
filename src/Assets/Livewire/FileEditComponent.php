<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Livewire\Traits\EmitsToNestables;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithForm;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class FileEditComponent extends Component
{
    use ShowsAsDialog;
    use WithFileUploads;
    use InteractsWithForm;
    use EmitsToNestables;

    public $parentId;

    public ?PreviewFile $previewFile = null;
    public ?PreviewFile $replacedPreviewFile = null;
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
            'externalAssetUpdated-' . $this->getId() => 'onExternalAssetUpdated',
            'assetUpdated-' . $this->getId() => 'onAssetUpdated',
        ];
    }

    public function open($value)
    {
        $this->setFile(is_array($value['previewfile']) ? PreviewFile::fromArray($value['previewfile']) : $value['previewfile']);

        $this->isOpen = true;
    }

    private function setFile(PreviewFile $previewFile)
    {
        $this->previewFile = $previewFile;
        $this->previewFile->loadOwners();

        $this->form['basename'] = $this->previewFile->getBaseName();

        $this->addAssetComponents();

        $this->extractFormComponents();
    }

    public function updatedFile(): void
    {
        if (! $this->replacedPreviewFile) {
            $this->replacedPreviewFile = $this->previewFile;
        }
        $this->previewFile = PreviewFile::fromTemporaryUploadedFile($this->file);
        $this->syncForm();
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

        $this->dispatch('assetUpdated-' . $this->parentId, $this->previewFile);

        $this->close();
    }

    public function close()
    {
        $this->reset(['previewFile', 'form', 'components']);
        $this->isOpen = false;
    }

    public function onExternalAssetUpdated()
    {
        // Update previewfile to reflect the external asset data
        $this->previewFile = PreviewFile::fromAsset(Asset::find($this->previewFile->mediaId));

        $this->dispatch('assetUpdated-' . $this->parentId, $this->previewFile);

        $this->close();
    }

    public function onAssetUpdated($previewFileArray): void
    {
        $this->previewFile = PreviewFile::fromArray($previewFileArray);
    }

    public function openImageCrop()
    {
        $this->emitToSibling('chief-wire::image-crop', 'open', ['previewfile' => $this->previewFile]);
    }

    public function openImageHotSpots()
    {
        $this->emitToSibling('chief-wire::hotspots', 'open', ['previewfile' => $this->previewFile]);
    }

    public function submit()
    {
        if ($this->replacedPreviewFile) {
            if ($this->replacedPreviewFile->mediaId) {
                app(FileApplication::class)->replaceMedia($this->replacedPreviewFile->mediaId, $this->previewFile->toUploadedFile());
                $this->previewFile = PreviewFile::fromAsset(Asset::find($this->replacedPreviewFile->mediaId));
            } else {
                $this->previewFile->id = $this->replacedPreviewFile->id;
            }
        }

        $this->validateForm(...$this->addDefaultBasenameValidation());

        if ($this->previewFile->mediaId) {
            app(FileApplication::class)->updateFileName($this->previewFile->mediaId, $this->form['basename']);
            app(FileApplication::class)->updateAssetData($this->previewFile->mediaId, $this->form);
        }

        // Update form values
        $this->syncForm();

        $this->dispatch('assetUpdated-' . $this->parentId, $this->previewFile);

        $this->close();
    }

    public function render()
    {
        return view('chief-assets::file-edit', [
            //
        ]);
    }
}
