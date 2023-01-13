<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Table\Concerns\HasColor;
use Thinktomorrow\Chief\Table\Concerns\HasUrl;

class TableColumnIcon extends TableColumn
{
    use HasUrl;
    use HasColor;

    protected string $view = 'chief-table::cells.icon';

    public function icon(string|null|int $value): static
    {
        return $this->value($value);
    }
}
