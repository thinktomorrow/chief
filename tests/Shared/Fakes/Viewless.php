<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;

class Viewless implements ViewableContract
{
    use Viewable;

    public function viewKey(): string
    {
        return 'viewless-key';
    }
}
