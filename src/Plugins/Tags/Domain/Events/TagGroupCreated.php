<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Domain\Events;

use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagGroupId;

class TagGroupCreated
{
    public function __construct(public readonly TagGroupId $tagGroupId) {}
}
