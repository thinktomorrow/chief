<?php

namespace Thinktomorrow\Chief\Admin\Tags\Events;

use Thinktomorrow\Chief\Admin\Tags\TagId;

class TagUpdated
{
    public function __construct(public readonly TagId $tagId)
    {

    }
}
