<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Thinktomorrow\Chief\Fragments\Actions\RenderFragments;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

final class FragmentsRenderer
{
    private FragmentRepository $fragmentRepository;
    private RenderFragments $renderFragments;

    public function __construct(FragmentRepository $fragmentRepository, RenderFragments $renderFragments)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->renderFragments = $renderFragments;
    }

    public function render(FragmentsOwner $owner, array $viewData): string
    {
        $fragmentables = $this->fragmentRepository->getByOwner($owner)->reject(function(Fragmentable $fragmentable) {
            return $fragmentable->fragmentModel()->isOffline();
        });

        return $this->renderFragments->render($fragmentables, $owner, $viewData);
    }
}
