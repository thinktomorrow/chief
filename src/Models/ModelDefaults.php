<?php

namespace Thinktomorrow\Chief\Models;

use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait ModelDefaults
{
    use ReferableModelDefault;
    use HasDynamicAttributes;

    /**
     * This is an optional method for the DynamicAttributes behavior and allows for
     * proper localized values to be returned. Here we provide the default in
     * advance in case the model decides to make use of DynamicAttributes.
     */
    public function dynamicLocales(): array
    {
        // TODO: get only the locales that are used by this model
        return ChiefSites::getLocales();
    }

    public function viewKey(): string
    {
        return (new ResourceKeyFormat(static::class))->getKey();
    }
}
