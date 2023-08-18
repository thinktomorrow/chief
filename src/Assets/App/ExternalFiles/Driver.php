<?php

namespace Thinktomorrow\Chief\Assets\App\ExternalFiles;

use Thinktomorrow\AssetLibrary\AssetContract;

interface Driver
{
    public function createAsset(string $idOrUrl): AssetContract;

    /**
     * This will fetch the actual file values via the driver API and
     * updates the media database record with the new values
     */
    public function updateAsset(AssetContract $asset, string $id): AssetContract;

    public function getCreateFormLabel(): string;
    public function getCreateFormDescription(): string;
}
