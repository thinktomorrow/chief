<?php

namespace Thinktomorrow\Chief\Models;

use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait ModelDefaults
{
    use HasDynamicAttributes;
    use ReferableModelDefault;

    /**
     * This is an optional method for the DynamicAttributes behavior and allows for
     * proper localized values to be returned. Here we provide the default in
     * advance in case the model decides to make use of DynamicAttributes.
     */
    public function dynamicLocales(): array
    {
        // TODO: get only the locales that are used by this model
        return ChiefLocales::fieldLocales();
    }

    public function viewKey(): string
    {
        return (new ResourceKeyFormat(static::class))->getKey();
    }
}
