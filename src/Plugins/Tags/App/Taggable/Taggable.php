<?php

namespace Thinktomorrow\Chief\Plugins\Tags\App\Taggable;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagRead;

interface Taggable
{
    /** @return TagRead[] */
    public function getTags(): Collection;

    public function tags(): MorphToMany;
}
