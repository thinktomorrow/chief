<?php

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

/**
 * @deprecated use Thinktomorrow\Chief\Models\ModelDefaults instead
 */
trait ModelDefaults
{
    use ReferableModelDefault;
    use Viewable;

    use InteractsWithAssets;
    use HasDynamicAttributes;

    /**
     * This is an optional method for the DynamicAttributes behavior and allows for
     * proper localized values to be returned. Here we provide the default in
     * advance in case the model decides to make use of DynamicAttributes.
     */
    public function dynamicLocales(): array
    {
        return ChiefSites::getLocales();
    }

    public function viewKey(): string
    {
        return (new ResourceKeyFormat(static::class))->getKey();
    }
}
