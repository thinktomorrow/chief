<?php

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Sites\ChiefLocales;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait ModelDefaults
{
    use ReferableModelDefault;
    use Viewable;

    use InteractsWithAssets;
    use HasDynamicAttributes{
        dynamicLocaleFallback as protected dynamicLocaleFallbackTrait;
    }

    /**
     * This is an optional method for the DynamicAttributes behavior and allows for
     * proper localized values to be returned. Here we provide the default in
     * advance in case the model decides to make use of DynamicAttributes.
     */
    public function dynamicLocales(): array
    {
        return ChiefLocales::locales();
    }

    protected function dynamicLocaleFallback(string $locale): null|string|array
    {
        if (property_exists($this, 'dynamicLocaleFallback')) {
            return $this->dynamicLocaleFallbackTrait($locale);
        }

        return ChiefLocales::fallbackLocales();
    }

    public function viewKey(): string
    {
        return (new ResourceKeyFormat(static::class))->getKey();
    }
}
