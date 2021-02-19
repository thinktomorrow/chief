<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Illuminate\Support\Collection;
use Illuminate\View\Concerns\ManagesLoops;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

final class RenderFragments
{
    use ManagesLoops;

    public function render(Collection $fragmentables, FragmentsOwner $owner, $viewData = []): string
    {
        // Validate each entry as a valid fragment object.
        $fragmentables->each(function (Fragmentable $fragmentable) {
        });

        // Init new loop object
        $this->loopsStack = [];
        $this->addLoop($fragmentables);

        return $fragmentables->reduce(function ($carry, Fragmentable $fragmentable) use ($fragmentables, $owner, $viewData) {
            $this->incrementLoopIndices();
            $loop = $this->getLastLoop();

            return $carry . $fragmentable->renderFragment($owner, $loop, $fragmentables, $viewData);
        }, '');
    }
}
