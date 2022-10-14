<?php

namespace Thinktomorrow\Chief\Table;

use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Table\Elements\BulkAction;
use Thinktomorrow\Chief\Table\Elements\TableColumn;
use Thinktomorrow\Chief\Table\Elements\TableHeader;

interface TableResource
{
    /**
     * The values of each row column.
     *
     * @return TableColumn[] iterable
     */
    public function getTableRow(Manager $manager, $model): iterable;

    /**
     * The bulk actions you wish to provide to the table. This will
     * automatically display the bulk checkboxes on each row.
     *
     * @return BulkAction[] iterable
     */
    public function getTableActions(Manager $manager): iterable;

    /**
     * Unique reference to each row. This value is used for the bulk action checkboxes.
     *
     * @param $model
     */
    public function getTableRowId($model): string;

    /**
     * The table headers are distilled from the first row of table columns.
     * You can opt to override this by returning a custom set of TableHeader[].
     *
     * @return TableHeader[] iterable
     */
    public function getTableHeaders(Manager $manager, $firstModel): iterable;

    /**
     * Display the table header as sticky or not. Defaults to false.
     */
    public function displayTableHeaderAsSticky(): bool;
}
