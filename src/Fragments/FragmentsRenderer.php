<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Support\Collection;
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
        $fragmentables = $this->getFragments($owner);

        return $this->renderFragments->render($fragmentables, $owner, $viewData);
    }

    public function getFragments(FragmentsOwner $owner): Collection
    {
        return $this->fragmentRepository->getByOwner($owner->ownerModel())->reject(function (Fragmentable $fragmentable) {
            return $fragmentable->fragmentModel()->isOffline();
        });
    }
}
