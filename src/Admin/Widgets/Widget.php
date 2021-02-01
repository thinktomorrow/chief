<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Widgets;

interface Widget
{
    public function renderAdminWidget($loop, $widgets): string;
}
