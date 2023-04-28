<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Read;

use Illuminate\Support\Collection;

interface TimeTableReadRepository
{
    public function getAll(): Collection;

    public function getAllTimeTablesForSelect(): array;
}