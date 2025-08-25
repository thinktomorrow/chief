<?php

namespace Thinktomorrow\Chief\Table\Columns\Presets;

use Thinktomorrow\Chief\Table\Columns\ColumnImage;

class AssetColumnImage extends ColumnImage
{
    public static function makeDefault(string $assetKey = 'image'): static
    {
        return static::make($assetKey)
            ->items(fn ($item) => $item->asset($assetKey) && $item->asset($assetKey)->exists() ? $item->asset($assetKey)->getUrl('thumb') : null);
    }
}
