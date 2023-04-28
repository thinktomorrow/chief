<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

interface HasTimeTable
{
    public function getTimeTableId(): ?TimeTableId;

    public function getTimeTableHours(string $locale): TimeTable;
}
