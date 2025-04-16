<?php

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\DuplicateFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Exceptions\SafeContextDeleteException;
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
            $this->detachFragment->handle($command->getContextId(), $fragmentModel->id);
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

    public function syncAllowedSites(SyncAllowedSites $command): void
    {
        $this->contextRepository
            ->getByOwner($command->getModelReference())
            ->each(fn ($context) => $context->setAllowedSites($command->getAllowedSites()))
            ->each(fn ($context) => $context->save())
            ->each(function ($context) use ($command) {

                $removedSites = array_diff($context->getActiveSites(), $command->getAllowedSites());

                foreach ($removedSites as $removedSite) {
                    $context->removeActiveSite($removedSite);
                }

                $context->save();
            });
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
