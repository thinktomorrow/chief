<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

class TimeTableUpdated
{
    public function __construct(public readonly TimeTableId $tagGroupId) {}
}
