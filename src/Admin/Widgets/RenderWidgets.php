<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Widgets;

use Illuminate\Support\Collection;
use Illuminate\View\Concerns\ManagesLoops;

final class RenderWidgets
{
    use ManagesLoops;

    public function render(Collection $widgets): string
    {
        // Validate each entry as a valid widget object.
        $widgets->each(function (Widget $widget) {
            //
        });

        // Init new loop object
        $this->loopsStack = [];
        $this->addLoop($widgets);

        return $widgets->reduce(function ($carry, Widget $widget) use ($widgets) {
            $this->incrementLoopIndices();
            $loop = $this->getLastLoop();

            return $carry . $widget->renderAdminWidget($loop, $widgets);
        }, '');
    }
}
