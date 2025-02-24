<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

class TimeTableDeleted
{
    public function __construct(public readonly TimeTableId $tagGroupId) {}
}
