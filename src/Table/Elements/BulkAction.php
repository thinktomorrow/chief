<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasEndpoint;
use Thinktomorrow\Chief\Table\Concerns\HasIcon;

class BulkAction extends Component
{
    use HasEndpoint;
    use HasIcon;

    protected string $view = 'chief-table::bulk-action';
}
