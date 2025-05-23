<?php

namespace Thinktomorrow\Chief\Models;

use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\PageState\UsesPageState;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Site\Visitable\VisitableDefaults;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSitesDefaults;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait PageDefaults
{
    use Archivable;
    use HasAllowedSitesDefaults;
    use HasDynamicAttributes;
    use InteractsWithAssets;
    use ReferableModelDefault;
    use UsesPageState;
    use Viewable;
    use VisitableDefaults;

    protected function getDynamicLocales(): array
    {
        return ChiefSites::locales();
    }

    protected function getDynamicFallbackLocales(): array
    {
        return ChiefSites::fallbackLocales();
    }

    protected function getAssetFallbackLocales(): array
    {
        return ChiefSites::assetFallbackLocales();
    }

    public function viewKey(): string
    {
        return (new ResourceKeyFormat(static::class))->getKey();
    }

    public function allowMultipleContexts(): bool
    {
        return config('chief.allow_multiple_contexts', false);
    }
}
