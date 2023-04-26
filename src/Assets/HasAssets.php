<?php

namespace Thinktomorrow\Chief\Assets;

use Spatie\MediaLibrary\HasMedia;

interface HasAssets extends HasMedia
{
    public function asset(string $type, ?string $locale = null): ?Asset;

    public function assets(string $type, string $locale = null): Collection;
}
