<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles\Cloudinary;

use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\Driver;

class CloudinaryDriver implements Driver
{
    public function createAsset(string $idOrUrl): AssetContract
    {
        throw new \RuntimeException('Cloudinary external files driver is not implemented.');
    }

    /**
     * This will fetch the actual file values via the driver API and
     * updates the media database record with the new values
     */
    public function updateAsset(AssetContract $asset, string $id): AssetContract
    {
        throw new \RuntimeException('Cloudinary external files driver is not implemented.');
    }

    public function getCreateFormLabel(): string
    {
        return 'Cloudinary URL';
    }

    public function getCreateFormDescription(): string
    {
        return 'Voeg een Cloudinary asset toe via URL of id.';
    }
}
