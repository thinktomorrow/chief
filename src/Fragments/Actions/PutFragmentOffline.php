<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Fragments\FragmentStatus;
use Thinktomorrow\Chief\Fragments\Events\FragmentPutOffline;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

class PutFragmentOffline
{
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    public function handle(int $fragmentModelId): void
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        $fragmentable->fragmentModel()->changeStatus(FragmentStatus::offline);
        $fragmentable->fragmentModel()->save();

        event(new FragmentPutOffline($fragmentModelId));
    }
}
