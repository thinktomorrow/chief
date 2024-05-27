<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Queries;

use Illuminate\Support\Collection;
use Illuminate\View\Concerns\ManagesLoops;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

final class RenderFragmentsOld
{
    use ManagesLoops;

    public function render(Collection $fragmentables, FragmentsOwner $owner, array $viewData = []): string
    {
        // Validate each entry as a valid fragment object.
        $fragmentables->each(function (Fragment $_fragmentable) {
        });

        // Init new loop object
        $this->loopsStack = [];
        $this->addLoop($fragmentables);

        return $fragmentables->reduce(function ($carry, Fragment $fragmentable) use ($owner, $viewData) {
            $this->incrementLoopIndices();
            $loop = $this->getLastLoop();

            return $carry . $fragmentable->renderFragment($owner, $loop, $viewData);
        }, '');
    }
}
