<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;

class DeleteFragment
{
    private FragmentRepository $fragmentRepository;

    private ContextRepository $contextRepository;

    private DetachAsset $detachAsset;

    public function __construct(FragmentRepository $fragmentRepository, ContextRepository $contextRepository, DetachAsset $detachAsset)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->contextRepository = $contextRepository;
        $this->detachAsset = $detachAsset;
    }

    /**
     * Delete the fragment entirely.
     */
    public function handle(string $fragmentId): void
    {
        $fragment = $this->fragmentRepository->find($fragmentId);

        try {
            $this->detachAsset->handleAll($fragment->getFragmentModel());
        } catch (\Exception $e) {
            report($e);
        }

        $fragment->getFragmentModel()->delete();
    }

    /**
     * When the fragment is detached from a given context. Here we check that if the fragment
     * is still shared (used by another context) then leave britney alone! If this
     * fragment is not shared by other contexts, it will be deleted entirely.
     */
    public function onFragmentDetached(FragmentDetached $event)
    {
        if ($this->contextRepository->countFragments($event->fragmentId) > 0) {
            return;
        }

        $this->handle($event->fragmentId);
    }
}
