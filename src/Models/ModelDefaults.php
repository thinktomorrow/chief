<?php

namespace Thinktomorrow\Chief\Models;

use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\ManagedModels\States\SimpleState\UsesSimpleState;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait ModelDefaults
{
    use HasDynamicAttributes;
    use InteractsWithAssets;
    use ReferableModelDefault;
    use UsesSimpleState;

    protected function getDynamicLocales(): array
    {
        return ChiefLocales::locales();
    }

    protected function getDynamicFallbackLocales(): array
    {
        return ChiefLocales::fallbackLocales();
    }

    protected function getAssetFallbackLocales(): array
    {
        return ChiefLocales::fallbackLocales();
    }

    public function viewKey(): string
    {
        return (new ResourceKeyFormat(static::class))->getKey();
    }
}
