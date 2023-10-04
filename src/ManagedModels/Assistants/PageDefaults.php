<?php

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Locale\LocalisableDefault;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\ManagedModels\States\PageState\UsesPageState;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\Publishable;
use Thinktomorrow\Chief\Site\Visitable\VisitableDefaults;

trait PageDefaults
{
    use ModelDefaults;

    use VisitableDefaults;
    use OwningFragments;
    use UsesPageState;
    use ShowsPageState;
    use Publishable;
    use Archivable;
    use LocalisableDefault;

    /**
     * Get all related models that have at least one fragment.
     */
    public function getRelatedOwners(): Collection
    {
        return static::where('id', '<>', $this->id)->get();
    }
}
