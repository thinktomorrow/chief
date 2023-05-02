<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Read;

interface TimeTableReadRepository
{
    public function getAllTimeTablesForSelect(): array;
}
