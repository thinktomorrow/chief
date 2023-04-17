<?php

namespace Thinktomorrow\Chief\Admin\Tags\Events;

use Thinktomorrow\Chief\Admin\Tags\TagId;

class TagCreated
{
    public function __construct(public readonly TagId $tagId)
    {

    }
}
