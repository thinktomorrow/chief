<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
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

        if($fragmentable->fragmentModel()->isOnline()) {
            return;
        }

        $fragmentable->fragmentModel()->setOnline();
        $fragmentable->fragmentModel()->save();

        event(new FragmentPutOnline($fragmentId));
    }
}
