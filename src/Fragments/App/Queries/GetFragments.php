<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\View\Concerns\ManagesLoops;
use Thinktomorrow\Chief\Fragments\App\ActiveContext\FragmentCollection;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

final class GetFragments
{
    use ManagesLoops;

    private FragmentRepository $fragmentRepository;

    private ContextRepository $contextRepository;

    public function __construct(ContextRepository $contextRepository, FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->contextRepository = $contextRepository;
    }

    /**
     * Get all fragments for the frontend.
     */
    public function get(string $contextId, string $locale): FragmentCollection
    {
        $fragmentCollection = $this->fragmentRepository->getTreeByContext($contextId, $locale);

        // When admin is logged in and this request is in preview mode, we allow to view all fragments
        if (PreviewMode::fromRequest()->check()) {
            // TODO: mark all offline fragments as such to better see the difference when admin is in preview mode
            return $fragmentCollection;
        }

        // We don't display offline fragments
        return $fragmentCollection->remove(function (Fragment $fragment) {
            return $fragment->fragmentModel()->isOffline();
        });
    }
}
