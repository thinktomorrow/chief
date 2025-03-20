<?php

namespace Thinktomorrow\Chief\Models;

use Illuminate\Support\Collection;
use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\PageState\UsesPageState;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Site\Visitable\VisitableDefaults;
use Thinktomorrow\Chief\Sites\BelongsToSitesDefault;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\Locales\LocalizedDefault;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait PageDefaults
{
    use Archivable;
    use BelongsToSitesDefault;
    use HasDynamicAttributes;
    use InteractsWithAssets;
    use LocalizedDefault;
    use ReferableModelDefault;
    use ShowsPageState;
    use UsesPageState;
    use Viewable;
    use VisitableDefaults;

    public function bootPageDefaults()
    {
        // Set dynamic locales
        // Set fallback map
        // Set fallback map for assets...
    }

    /**
     * This is an optional method for the DynamicAttributes behavior and allows for
     * proper localized values to be returned. Here we provide the default in
     * advance in case the model decides to make use of DynamicAttributes.
     */
    public function getDynamicLocales(): array
    {
        // TODO: test if this works consistently...
        return ChiefSites::all()->filterByLocales($this->getSiteLocales())->getLocales();
        //        return ChiefLocales::locales();
    }

    public function getFallbackLocales(): array {}

    public function viewKey(): string
    {
        return (new ResourceKeyFormat(static::class))->getKey();
    }

    /**
     * Get all related models that have at least one fragment.
     */
    public function getRelatedOwners(): Collection
    {
        return static::where('id', '<>', $this->id)->get();
    }
}
