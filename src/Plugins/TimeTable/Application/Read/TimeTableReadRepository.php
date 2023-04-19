<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Application\Read;

use Illuminate\Support\Collection;

interface TimeTableReadRepository
{
    public function getAll(): Collection;

    public function getAllTimeTablesForSelect(): array;
}
