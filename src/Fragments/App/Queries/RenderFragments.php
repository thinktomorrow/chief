<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Support\Collection;
use Illuminate\View\Concerns\ManagesLoops;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\Presets\Page;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

final class RenderFragments
{
    use ManagesLoops;

    private FragmentRepository $fragmentRepository;
    private ContextRepository $contextRepository;

    public function __construct(ContextRepository $contextRepository, FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->contextRepository = $contextRepository;
    }

//    public function render(FragmentsOwner $owner, string $locale, array $viewData = []): string
    public function render(ContextOwner $owner, string $locale, array $viewData = []): string
    {
        // Get entire tree of all sections and fragments...
        $fragments = $this->getFragments($owner, $locale);

        // VINE!!
        // fragment 1
        // fragment 2
        // fragment 3
            // fragment 4
            // fragment 5
        // fragment 6

        // section is a fragment but with children on its own.
        // sectionTree is the ancestor tree of the current section
        // Page, context, section, sectionTree, fragments

        // Here we also add following viewData:
        //  - context
        //  - rootFragment and fragmentAncestors (if nested)
        //  - page should already be added

        // Render fragment tree

        return $this->renderFragments($fragments, $viewData);
    }

    // TODO: collection should be FragmentCollection (nested tree). Render and loop could be in this collection
    private function renderFragments(Collection $fragmentables, array $viewData = []): string
    {
        // Validate each entry as a valid fragment object.
        $fragmentables->each(function (Fragmentable $_fragmentable) {
        });

        // Init new loop object
        $this->loopsStack = [];
        $this->addLoop($fragmentables);

        return $fragmentables->reduce(function ($carry, Fragmentable $fragmentable) use ($owner, $viewData) {
            $this->incrementLoopIndices();
            $loop = $this->getLastLoop();

            return $carry . $fragmentable->renderFragment($owner, $loop, $viewData);
        }, '');
    }

    public function getFragments(ContextOwner $owner, string $locale): Collection
    {
        // Find active context for this owner and locale

        // TODO: The current chief_urls record contains the context, not the owner...
        // Chief url is the owner ?
        // Page - url - context
        if(!$contextId = $owner->activeContextId($locale)) {
            return new Collection();
        };

        // Get fragments for given context
        $fragments = $this->fragmentRepository->getByContext($contextId);

        // When admin is logged in and this request is in preview mode, we allow to view all fragments
        if (PreviewMode::fromRequest()->check()) {
            return $fragments;
        }

        // We don't display offline fragments
        return $fragments->reject(function (Fragmentable $fragmentable) {
            return $fragmentable->fragmentModel()->isOffline();
        });
    }
}
