<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Forms\Concerns\HasLayoutType;

class TableColumnLabel extends TableColumn
{
    use HasLayoutType;

    protected string $view = 'chief-table::cells.label';

    public function label(string|null|int $value): static
    {
        return $this->value($value);
    }
}
