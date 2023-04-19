<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Application\HasTimeTable;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\TagRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;

interface HasTimeTable
{
    public function getTimeTableId(): ?TimeTableId;
}
