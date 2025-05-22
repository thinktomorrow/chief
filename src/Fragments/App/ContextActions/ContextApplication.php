<?php

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\DuplicateFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyDetached;
use Thinktomorrow\Chief\Fragments\Exceptions\SafeContextDeleteException;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Sites\Actions\SyncActiveSites;

class ContextApplication
{
    private ContextRepository $contextRepository;

    private DuplicateFragment $duplicateFragment;

    private DetachFragment $detachFragment;

    private SyncActiveSites $syncActiveSites;

    public function __construct(DuplicateFragment $duplicateFragment, ContextRepository $contextRepository, DetachFragment $detachFragment, SyncActiveSites $syncActiveSites)
    {
        $this->duplicateFragment = $duplicateFragment;
        $this->contextRepository = $contextRepository;
        $this->detachFragment = $detachFragment;
        $this->syncActiveSites = $syncActiveSites;
    }

    public function create(CreateContext $command): string
    {
        $context = $this->contextRepository->create(
            $command->getModelReference(),
            $command->getAllowedSites(),
            $command->getActiveSites(),
            $command->getTitle()
        );

        $this->syncActiveSites->handle(
            $context,
            $this->contextRepository->getByOwner($context->owner->modelReference())->reject(fn ($c) => $c->id == $context->id)
        );

        return (string) $context->id;
    }

    public function update(UpdateContext $command): void
    {
        $context = $this->contextRepository->find($command->getContextId());

        $context->update([
            'title' => $command->getTitle(),
            'allowed_sites' => $command->getAllowedSites(),
            'active_sites' => $command->getActiveSites(),
        ]);

        $this->syncActiveSites->handle(
            $context,
            $this->contextRepository->getByOwner($context->owner->modelReference())->reject(fn ($c) => $c->id == $context->id)
        );
    }

    public function delete(DeleteContext $command): void
    {
        $context = $this->contextRepository->find($command->getContextId());

        foreach ($context->fragments()->get() as $fragmentModel) {
            try {
                $this->detachFragment->handle($command->getContextId(), $fragmentModel->id);
            } catch (FragmentAlreadyDetached $e) {
                // Ignore this exception, it means the fragment is already detached from the context or that the fragment is no longer present.
            }

        }

        $context->delete();
    }

    public function safeDelete(DeleteContext $command): void
    {
        $this->preventUnsafeDeletion($command->getContextId());

        $this->delete($command);
    }

    public function duplicate(DuplicateContext $command): string
    {
        $sourceContext = $this->contextRepository->find($command->getSourceContextId());
        $targetContext = $this->contextRepository->create($command->getTargetModel()->modelReference(), $sourceContext->getAllowedSites(), $sourceContext->getActiveSites(), ($sourceContext->title ? $sourceContext->title.' (copy)' : null));

        /** @var FragmentModel $fragment */
        foreach ($sourceContext->rootFragments as $index => $fragment) {
            $this->duplicateFragment->handle($fragment, $sourceContext->id, $targetContext->id, null, $index);
        }

        return $targetContext->id;
    }

    /**
     * If the context owner changed allowed sites selection, we remove
     * any sites on the contexts that should no longer be present.
     */
    public function syncSites(SyncSites $command): void
    {
        $contexts = $this->contextRepository
            ->getByOwner($command->getModelReference());

        // If only one context is present, we make sure it matches the given sites
        if ($contexts->count() == 1) {
            /** @var ContextModel $context */
            $context = $contexts->first();
            $context->setAllowedSites($command->getAllowedSites());
            $context->setActiveSites($command->getAllowedSites());
            $context->save();

            return;
        }

        // Remove any sites that are no longer allowed
        $contexts
            ->each(function ($context) use ($command) {

                $removedSites = array_diff($context->getAllowedSites(), $command->getAllowedSites());

                foreach ($removedSites as $removedSite) {
                    $context->removeAllowedSite($removedSite);
                }
            })
            ->each(function ($context) use ($command) {

                $removedSites = array_diff($context->getActiveSites(), $command->getAllowedSites());

                foreach ($removedSites as $removedSite) {
                    $context->removeActiveSite($removedSite);
                }
            })->each(fn ($context) => $context->save());
    }

    /**
     * @throws SafeContextDeleteException
     */
    public function preventUnsafeDeletion(string $contextId): void
    {
        $context = $this->contextRepository->find($contextId);
        $contexts = $this->contextRepository->getByOwner($context->owner->modelReference());

        if (count($context->getActiveSites()) > 0) {
            throw new SafeContextDeleteException('The context ['.$context->id.'] is still in active use by a site and cannot be deleted.');
        }

        if ($contexts->count() < 2) {
            throw new SafeContextDeleteException('The context ['.$context->id.'] is the last one for this model and cannot be deleted.');
        }
    }
}
