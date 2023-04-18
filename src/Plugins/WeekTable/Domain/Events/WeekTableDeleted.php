<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Domain\Events;

use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\WeekTableId;

class WeekTableDeleted
{
    public function __construct(public readonly WeekTableId $tagGroupId)
    {

    }
}
