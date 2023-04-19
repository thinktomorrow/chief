<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

class TimeTableCreated
{
    public function __construct(public readonly TimeTableId $tagGroupId)
    {

    }
}
