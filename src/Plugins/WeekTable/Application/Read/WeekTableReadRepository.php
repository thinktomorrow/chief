<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Application\Read;

use Illuminate\Support\Collection;

interface WeekTableReadRepository
{
    public function getAll(): Collection;

    public function getAllWeekTablesForSelect(): array;
}
