<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\Actions\RenderFragments;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

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
        $fragmentables = $this->getFragments($owner);

        return $this->renderFragments->render($fragmentables, $owner, $viewData);
    }

    public function getFragments(FragmentsOwner $owner): Collection
    {
        $fragments = $this->fragmentRepository->getByOwner($owner->ownerModel());

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
