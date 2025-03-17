<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentPutOnline;

class PutFragmentOnline
{
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    public function handle(string $fragmentId): void
    {
        $fragmentable = $this->fragmentRepository->find($fragmentId);

        if ($fragmentable->getFragmentModel()->isOnline()) {
            return;
        }

        $fragmentable->getFragmentModel()->setOnline();
        $fragmentable->getFragmentModel()->save();

        event(new FragmentPutOnline($fragmentId));
    }
}
