<?php

namespace Thinktomorrow\Chief\Admin\Tags\Events;

use Thinktomorrow\Chief\Admin\Tags\TagGroupId;

class TagGroupDeleted
{
    public function __construct(public readonly TagGroupId $tagGroupId)
    {

    }
}
