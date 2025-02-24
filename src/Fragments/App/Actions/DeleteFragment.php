<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;

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
            $this->detachAsset->handleAll($fragment->fragmentModel());
        } catch (\Exception $e) {
            report($e);
        }

        $fragment->fragmentModel()->delete();
    }

    /**
     * When the fragment is detached from a given context. Here we check that if the fragment
     * is still shared (used by another context) then leave britney alone! If this
     * fragment is not shared by other contexts, it will be deleted entirely.
     */
    public function onFragmentDetached(FragmentDetached $event)
    {
        // TODO: also check if it is nested and owner by another fragment, not just context...
        if ($this->contextRepository->countByFragment($event->fragmentId) > 0) {
            return;
        }

        $this->handle($event->fragmentId);
    }
}
