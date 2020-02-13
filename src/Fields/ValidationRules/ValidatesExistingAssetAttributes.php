<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\ValidationRules;

use Thinktomorrow\AssetLibrary\Asset;

trait ValidatesExistingAssetAttributes
{
    protected function refersToExistingAsset($value): bool
    {
        // Check if id is passed first
        if(!preg_match('/^[1-9][0-9]*$/', $value)) return false;

        return !is_null($this->existingAsset($value));
    }

    protected function existingAsset($value): ?Asset
    {
        return Asset::where('id', $value)->first();
    }

    private function isValidJson($string): bool
    {
        try {
            json_decode($string);
        } catch (\Exception $e) {
            return false;
        }

        return (json_last_error() == JSON_ERROR_NONE);
    }

    protected function validateAssetDimensions(Asset $asset, $parameters)
    {
        $filepath = $asset->media->first()->getPath();

        if (! $sizeDetails = @getimagesize($filepath)) {
            return false;
        }

        $this->requireParameterCount(1, $parameters, 'dimensions');
        [$width, $height] = $sizeDetails;

        return $this->dimensionsCheck($width, $height, $parameters);
    }

    /** Taken from the Laravel ValidatedAttributes::validateDimensions */
    protected function dimensionsCheck($width, $height, $parameters): bool
    {
        $parameters = $this->parseNamedParameters($parameters);

        if ($this->failsBasicDimensionChecks($parameters, $width, $height) ||
            $this->failsRatioCheck($parameters, $width, $height)) {
            return false;
        }

        return true;
    }

    protected function validateAssetMimetypes(Asset $asset, $parameters)
    {
        return (in_array($asset->getMimeType(), $parameters) ||
            in_array(explode('/', $asset->getMimeType())[0].'/*', $parameters));
    }

    protected function validateAssetMax(Asset $asset, $parameters)
    {
        // Asset size in bytes
        $assetSize = $asset->media->first()->size;

        return ($assetSize / 1024) <= $parameters[0];
    }

    protected function validateAssetMin(Asset $asset, $parameters)
    {
        // Asset size in bytes
        $assetSize = $asset->media->first()->size;

        return ($assetSize / 1024) >= $parameters[0];
    }
}
