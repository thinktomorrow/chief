<?php

namespace Thinktomorrow\Chief\Admin\Tags;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Admin\Tags\Read\TagRead;

interface Taggable
{
    /** @return TagRead[] */
    public function getTags(): Collection;

    public function tags(): MorphToMany;
}
