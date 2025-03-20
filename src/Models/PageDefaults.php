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
use Thinktomorrow\Chief\Sites\BelongsToSitesDefaults;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

trait PageDefaults
{
    use Archivable;
    use BelongsToSitesDefaults;
    use HasDynamicAttributes;
    use InteractsWithAssets;
    use ReferableModelDefault;
    use ShowsPageState;
    use UsesPageState;
    use Viewable;
    use VisitableDefaults;

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
