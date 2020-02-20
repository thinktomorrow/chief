<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Support\Collection;

trait ChecksExistingAssets
{
    protected function looksLikeAnAssetId($value): bool
    {
        if (!is_string($value) && !is_int($value)) {
            return false;
        }

        // check if passed value is an ID
        return (bool)preg_match('/^[1-9][0-9]*$/', (string)$value);
    }

    protected function isKeyAnAttachedAssetId(Collection $existingAttachedAssets, string $locale, string $type, $assetId): bool
    {
        return ($this->looksLikeAnAssetId($assetId) && $existingAttachedAssets
                ->where('pivot.locale', $locale)
                ->where('pivot.type', $type)
                ->where('pivot.asset_id', $assetId)
                ->count());
    }
}
