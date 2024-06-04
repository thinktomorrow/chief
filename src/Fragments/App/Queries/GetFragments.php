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

    public function get(string $contextId): FragmentCollection
    {
        $fragmentCollection = $this->fragmentRepository->getTreeByContext($contextId);

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

    //    public function render(FragmentsOwner $owner, string $locale, array $viewData = []): string
    /**
     * @deprecated use GetFragments::get() instead.
     *
     *  This render does not make use of the component rendering of fragments.
     *  Best to loop the fragments yourself in the view like:
     *  @foreach(getFragments() as $fragment) {{ $fragment->render() }} @endforeach
     */
    public function render(string $contextId): string
    {
        $fragments = $this->get($contextId);

        $output = '';

        foreach($fragments as $fragment) {
            $output .= $fragment->toHtml();
        }

        return $output;
    }
}
