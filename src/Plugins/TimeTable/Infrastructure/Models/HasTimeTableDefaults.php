<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

trait HasTimeTableDefaults
{
    public function getTimeTableId(): ?TimeTableId
    {
        return $this->timetable_id ? TimeTableId::fromString($this->timetable_id) : null;
    }
}
