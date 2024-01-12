<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;
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

    public function render(FragmentsOwner $owner, string $locale, array $viewData = []): string
    {
        $fragmentables = $this->getFragments($owner, $locale);

        return $this->renderFragments->render($fragmentables, $owner, $viewData);
    }

    public function getFragments(FragmentsOwner $owner, string $locale): Collection
    {
        $fragments = $this->fragmentRepository->getByOwner($owner, $locale);

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
