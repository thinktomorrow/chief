<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Table\Concerns\HasUrl;

class TableCellLink extends TableCell
{
    use HasUrl;

    protected string $view = 'chief-table::cells.link';

    public static function make(string|int|null $url)
    {
        $component = parent::make($url);
        $component->url($url);

        return $component;
    }
}
