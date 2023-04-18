<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Application\HasWeekTable;

use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\WeekTableId;

interface HasWeekTable
{
    public function getWeekTableId(): ?WeekTableId;
}
