<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Domain\Events;

use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagId;

class TagUpdated
{
    public function __construct(public readonly TagId $tagId)
    {

    }
}
