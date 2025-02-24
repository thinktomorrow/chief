<?php

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\PageState\UsesPageState;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\Publishable;
use Thinktomorrow\Chief\Site\Visitable\VisitableDefaults;
use Thinktomorrow\Chief\Sites\BelongsToSitesDefault;

trait PageDefaults
{
    use Archivable;
    use BelongsToSitesDefault;
    use ModelDefaults;
    use OwningFragments;
    use Publishable;
    use ShowsPageState;
    use UsesPageState;
    use VisitableDefaults;

    /**
     * Get all related models that have at least one fragment.
     */
    public function getRelatedOwners(): Collection
    {
        return static::where('id', '<>', $this->id)->get();
    }
}
