<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Application\HasTimeTable;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

interface HasTimeTable
{
    public function getTimeTableId(): ?TimeTableId;
}
