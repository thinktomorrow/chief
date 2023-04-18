<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Application\HasWeekTable;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\TagRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\WeekTableId;

interface HasWeekTable
{
    public function getWeekTableId(): ?WeekTableId;
}
