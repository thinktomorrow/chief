<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;

final class AttachFragment
{
    private ReorderFragments $reorderFragments;

    private FragmentRepository $fragmentRepository;

    private ContextRepository $contextRepository;

    public function __construct(ReorderFragments $reorderFragments, FragmentRepository $fragmentRepository, ContextRepository $contextRepository)
    {
        $this->reorderFragments = $reorderFragments;
        $this->fragmentRepository = $fragmentRepository;
        $this->contextRepository = $contextRepository;
    }

    public function handle(string $contextId, string $fragmentId, ?string $parentId, int $order, array $data = []): void
    {
        $sourceContextId = $this->findSourceContextId($fragmentId, $contextId);

        $context = ContextModel::findOrFail($contextId);

        // Protect against duplicate addition...
        if ($context->fragments()->where('id', $fragmentId)->exists()) {
            throw new FragmentAlreadyAdded('Fragment ['.$fragmentId.'] was already added to context ['.$context->id.']');
        }

        $indices = $this->fetchSortIndices($context, $order, $fragmentId, $parentId);

        $context->fragments()->attach($fragmentId, array_merge(['parent_id' => $parentId], $data));

        $this->reorderFragments->handle($contextId, $indices, $parentId);

        event(new FragmentAttached($fragmentId, $context->id));

        if (! $sourceContextId) {
            return;
        }

        $this->attachNestedFragments($sourceContextId, $contextId, $fragmentId);
    }

    private function attachNestedFragments(string $sourceContextId, string $targetContextId, string $parentFragmentId): void
    {
        $parent = $this->fragmentRepository->getFragmentCollection($sourceContextId)
            ->find(fn (Fragment $fragment) => $fragment->getFragmentId() === $parentFragmentId);

        if (! $parent) {
            return;
        }

        foreach ($parent->getChildNodes() as $child) {
            $this->handle(
                $targetContextId,
                $child->getFragmentId(),
                $parentFragmentId,
                $child->getFragmentModel()->pivot->order,
            );
        }
    }

    private function findSourceContextId(string $fragmentId, string $targetContextId): ?string
    {
        return $this->contextRepository
            ->getContextsByFragment($fragmentId)
            ->first(fn (ContextModel $context) => $context->id !== $targetContextId)
            ?->id;
    }

    private function fetchSortIndices(ContextModel $context, int $order, string $fragmentId, ?string $parentId): array
    {
        $query = $context->fragments();

        if ($parentId) {
            $query->wherePivot('parent_id', $parentId);
        } else {
            $query->wherePivotNull('parent_id');
        }

        $indices = $query->get()->map(function ($fragment) {
            return $fragment->id;
        })->values()->all();

        array_splice($indices, $order, 0, $fragmentId);

        return $indices;
    }
}
