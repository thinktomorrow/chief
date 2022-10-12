<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasEndpoint;

class BulkAction extends Component
{
    use HasEndpoint;

    protected string $view = 'chief-table::bulk-action';
}
