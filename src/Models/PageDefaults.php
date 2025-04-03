<?php

namespace Thinktomorrow\Chief\Models;

use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\PageState\UsesPageState;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Site\Visitable\VisitableDefaults;
use Thinktomorrow\Chief\Sites\HasSiteLocalesDefaults;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait PageDefaults
{
    use Archivable;
    use HasDynamicAttributes;
    use HasSiteLocalesDefaults;
    use InteractsWithAssets;
    use ReferableModelDefault;
    use ShowsPageState;
    use UsesPageState;
    use Viewable;
    use VisitableDefaults;

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
