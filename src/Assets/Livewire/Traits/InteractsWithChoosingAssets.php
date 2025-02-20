<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;

trait InteractsWithChoosingAssets
{
    public function onAssetsChosen(array $assetIds)
    {
        if (! $this->allowMultiple) {
            // Assert only one file is added.
            $assetIds = (array) reset($assetIds);

            foreach ($this->previewFiles as $previewFile) {
                $previewFile->isQueuedForDeletion = true;
            }
        }

        // If asset is already present in the files array, we don't allow it to be added
        $assetIds = collect($assetIds)
            ->reject(fn ($assetId) => ! is_null($this->findPreviewFileIndex($assetId)))
            ->all();

        Asset::whereIn('id', $assetIds)->get()->each(function (AssetContract $asset) {
            $previewFile = PreviewFile::fromAsset($asset);
            $previewFile->isAttachedToModel = false;

            $this->previewFiles[] = $previewFile;
        });
    }

    public function openFilesChoose()
    {
        $this->emitDownTo('chief-wire::file-field-choose', 'open', [
            'existingAssetIds' => collect($this->previewFiles)->map(fn ($previewFile) => $previewFile->id)->all(),
            'allowExternalFiles' => $this->allowExternalFiles ?? true,
        ]);
    }

    public function openFilesChooseExternal()
    {
        $this->emitDownTo('chief-wire::file-field-choose-external', 'open', []);
    }
}
