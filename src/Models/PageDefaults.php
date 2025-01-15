<?php

namespace Thinktomorrow\Chief\Models;

use Illuminate\Support\Collection;
use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\ManagedModels\Assistants\ShowsPageState;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\PageState\UsesPageState;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Site\Visitable\VisitableDefaults;
use Thinktomorrow\Chief\Sites\BelongsToSitesDefault;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait PageDefaults
{
    use ReferableModelDefault;
    use Viewable;
    use InteractsWithAssets;
    use HasDynamicAttributes;

    use VisitableDefaults;
    use OwningFragments;
    use UsesPageState;
    use ShowsPageState;
    use Archivable;
    use BelongsToSitesDefault;

    /**
     * This is an optional method for the DynamicAttributes behavior and allows for
     * proper localized values to be returned. Here we provide the default in
     * advance in case the model decides to make use of DynamicAttributes.
     */
    public function dynamicLocales(): array
    {
        // TODO: get only the locales that are used by this model
        return ChiefSites::locales();
    }

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
