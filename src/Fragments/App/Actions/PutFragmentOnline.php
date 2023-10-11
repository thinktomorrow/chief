<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\FragmentStatus;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentPutOnline;

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

        $fragmentable->fragmentModel()->changeStatus(FragmentStatus::online);
        $fragmentable->fragmentModel()->save();

        event(new FragmentPutOnline($fragmentId));
    }
}
