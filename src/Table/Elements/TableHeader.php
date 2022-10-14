<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Table\Concerns\HasSortable;

class TableHeader extends Component
{
    use HasSortable;

    protected string $view = 'chief-table::table-head';

    public static function fromTableColumn(TableColumn $tableColumn): static
    {
        $header = (new static($tableColumn->getTitle()));

        if($description = $tableColumn->getDescription()) {
            $header->description($description);
        }

        return $header;
    }
}
