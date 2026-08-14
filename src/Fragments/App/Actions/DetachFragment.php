<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyDetached;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;

class DetachFragment
{
    private FragmentRepository $fragmentRepository;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

    /**
     * Detach a fragment from a given context.
     * Keep the fragment model itself intact
     */
    public function handle(string $contextId, string $fragmentId): void
    {
        $context = ContextModel::findOrFail($contextId);

        $this->detachNestedFragments($contextId, $fragmentId);

        if (! $context->fragments()->where('id', $fragmentId)->exists()) {
            throw new FragmentAlreadyDetached('Fragment ['.$fragmentId.'] already detached or does not exist in context ['.$contextId.']');
        }

        $context->fragments()->detach($fragmentId);

        event(new FragmentDetached($fragmentId, $context->id));
    }

    private function detachNestedFragments(string $contextId, string $fragmentId): void
    {
        $parent = $this->fragmentRepository->getFragmentCollection($contextId)
            ->find(fn (Fragment $fragment) => $fragment->getFragmentId() === $fragmentId);

        if (! $parent) {
            return;
        }

        foreach ($parent->getChildNodes() as $child) {
            $this->handle($contextId, $child->getFragmentId());
        }
    }
}
