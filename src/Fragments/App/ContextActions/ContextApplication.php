<?php

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\DuplicateFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
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
            $command->getSites(),
            $command->getTitle()
        );

        return (string) $context->id;
    }

    public function update(UpdateContext $command): void
    {
        $context = $this->contextRepository->find($command->getContextId());

        $context->update([
            'title' => $command->getTitle(),
            'sites' => $command->getSites(),
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

    public function duplicate(DuplicateContext $command): void
    {
        $sourceContext = $this->contextRepository->find($command->getSourceContextId());
        $targetContext = $this->contextRepository->create($command->getTargetModel(), $sourceContext->getSiteLocales());

        /** @var FragmentModel $fragment */
        foreach ($sourceContext->rootFragments as $index => $fragment) {
            $this->duplicateFragment->handle($fragment, $sourceContext->id, $targetContext->id, null, $index);
        }
    }
}
