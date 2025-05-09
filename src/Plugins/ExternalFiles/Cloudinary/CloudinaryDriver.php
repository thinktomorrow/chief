<?php

namespace Thinktomorrow\Chief\Plugins\ExternalFiles\Cloudinary;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\Driver;

class CloudinaryDriver implements Driver
{
    /**
     * This will fetch the actual file values via the driver API and
     * updates the media database record with the new values
     */
    public function updateMedia(Asset $asset, string $url, array $data): void
    {
        // dd($url);
        // Extract ID from url

        // CALL API for new info

        // Set media record values:
        // filename,
        // thumbnail image
    }
}
