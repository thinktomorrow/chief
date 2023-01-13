<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Table\Concerns\HasUrl;

class TableColumnLink extends TableColumn
{
    use HasUrl;

    protected string $view = 'chief-table::cells.link';

    public function label(string|null|int $value): static
    {
        return $this->value($value);
    }
}
