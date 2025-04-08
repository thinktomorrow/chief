<?php

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\DuplicateFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Exceptions\SafeContextDeleteException;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;

class ContextApplication
{
    private ContextRepository $contextRepository;

    private DuplicateFragment $duplicateFragment;

    private DetachFragment $detachFragment;

    public function __construct(DuplicateFragment $duplicateFragment, ContextRepository $contextRepository, DetachFragment $detachFragment)
    {
        $this->duplicateFragment = $duplicateFragment;
        $this->contextRepository = $contextRepository;
        $this->detachFragment = $detachFragment;
    }

    public function create(CreateContext $command): string
    {
        $context = $this->contextRepository->create(
            $command->getModelReference(),
            $command->getLocales(),
            $command->getActiveSites(),
            $command->getTitle()
        );

        return (string) $context->id;
    }

    public function update(UpdateContext $command): void
    {
        $context = $this->contextRepository->find($command->getContextId());

        $context->update([
            'title' => $command->getTitle(),
            'locales' => $command->getLocales(),
            'active_sites' => $command->getActiveSites(),
        ]);
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

    public function duplicate(DuplicateContext $command): void
    {
        $sourceContext = $this->contextRepository->find($command->getSourceContextId());
        $targetContext = $this->contextRepository->create($command->getTargetModel()->modelReference(), $sourceContext->getSiteLocales(), $sourceContext->getActiveSites(), ($sourceContext->title ? $sourceContext->title.' (copy)' : null));

        /** @var FragmentModel $fragment */
        foreach ($sourceContext->rootFragments as $index => $fragment) {
            $this->duplicateFragment->handle($fragment, $sourceContext->id, $targetContext->id, null, $index);
        }
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
