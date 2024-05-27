<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Fragments\Events\FragmentPutOffline;
use Thinktomorrow\Chief\Fragments\Models\FragmentRepository;

class PutFragmentOffline
{
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    public function handle(string $fragmentId): void
    {
        $fragmentable = $this->fragmentRepository->find($fragmentId);

        if($fragmentable->fragmentModel()->isOffline()) {
            return;
        }

        $fragmentable->fragmentModel()->setOffline();
        $fragmentable->fragmentModel()->save();

        event(new FragmentPutOffline($fragmentId));
    }
}
