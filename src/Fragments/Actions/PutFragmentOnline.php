<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Actions;

use Thinktomorrow\Chief\Fragments\FragmentStatus;
use Thinktomorrow\Chief\Fragments\Events\FragmentPutOnline;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

class PutFragmentOnline
{
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    public function handle(int $fragmentModelId): void
    {
        $fragmentable = $this->fragmentRepository->find($fragmentModelId);

        $fragmentable->fragmentModel()->changeStatus(FragmentStatus::online);
        $fragmentable->fragmentModel()->save();

        event(new FragmentPutOnline($fragmentModelId));
    }
}
