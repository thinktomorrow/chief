<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Render;

use Illuminate\View\Concerns\ManagesLoops;
use Thinktomorrow\Chief\Fragments\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Models\FragmentRepository;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Vine\Node;

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
    public function render(string $contextId): string
    {
        // Get entire tree of all sections and fragments...
        $fragments = $this->getFragments($contextId);

        $output = '';

        foreach($fragments as $rootNode) {

            // render fragments like components...

            // Render fragment should be recursive
            // pass page, owner (page, section), context, section, sectionTree, fragments, ...
            $output .= $rootNode->getNodeEntry()->toHtml();
            //TODO: change to render() method (from component)
        }

        return $output;

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

        //return $this->renderFragments($fragments, $viewData);
    }

    // TODO: collection should be FragmentCollection (nested tree). Render and loop could be in this collection
    //    private function renderFragments(FragmentCollection $fragmentables, array $viewData = []): string
    //    {
    //        // Validate each entry as a valid fragment object.
    //        $fragmentables->each(function (Fragment $_fragmentable) {
    //        });
    //
    //        // Init new loop object
    //        $this->loopsStack = [];
    //        $this->addLoop($fragmentables);
    //
    //        return $fragmentables->reduce(function ($carry, Fragment $fragmentable) use ($owner, $viewData) {
    //            $this->incrementLoopIndices();
    //            $loop = $this->getLastLoop();
    //
    //            return $carry . $fragmentable->renderFragment($owner, $loop, $viewData);
    //        }, '');
    //    }

    public function getFragments(string $contextId): FragmentCollection
    {
        $fragmentCollection = $this->fragmentRepository->getTreeByContext($contextId);

        // When admin is logged in and this request is in preview mode, we allow to view all fragments
        if (PreviewMode::fromRequest()->check()) {
            // TODO: mark all offline fragments as such
            return $fragmentCollection;
        }

        // We don't display offline fragments
        return $fragmentCollection->remove(function (Node $node) {
            return $node->getNodeEntry()->fragmentModel()->isOffline();
        });
    }
}
