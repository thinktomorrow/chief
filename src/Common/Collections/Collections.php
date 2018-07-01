<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\FlatReferences\Types\CollectionFlatReference;

class Collections extends Collection
{
    /**
     * List of available collection keys and their corresponding models
     * @return array
     */
    public static function available(): array
    {
        return array_merge(
            config('thinktomorrow.chief.collections.pages', []),
            config('thinktomorrow.chief.collections.modules', [])
        );
    }
}
