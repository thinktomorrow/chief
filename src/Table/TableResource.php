<?php

namespace Thinktomorrow\Chief\Table;

use Thinktomorrow\Chief\Managers\Manager;

interface TableResource
{
    public function getTableColumns(): iterable;

    public function getTableRowId($model): string;

    public function getTableRow($model, $manager): iterable;

    public function getTableActions(Manager $manager): iterable;

    public function displayTableHeaderAsSticky(): bool;
}
