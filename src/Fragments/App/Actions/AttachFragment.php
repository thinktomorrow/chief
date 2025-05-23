<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;

final class AttachFragment
{
    private ReorderFragments $reorderFragments;

    public function __construct(ReorderFragments $reorderFragments)
    {
        $this->reorderFragments = $reorderFragments;
    }

    public function handle(string $contextId, string $fragmentId, ?string $parentId, int $order, array $data = []): void
    {
        $context = ContextModel::findOrFail($contextId);

        // Protect against duplicate addition...
        if ($context->fragments()->where('id', $fragmentId)->exists()) {
            throw new FragmentAlreadyAdded('Fragment ['.$fragmentId.'] was already added to context ['.$context->id.']');
        }

        $indices = $this->fetchSortIndices($context, $order, $fragmentId);

        $context->fragments()->attach($fragmentId, array_merge(['parent_id' => $parentId], $data));

        $this->reorderFragments->handle($contextId, $indices);

        event(new FragmentAttached($fragmentId, $context->id));
    }

    /**
     * @param  int  $fragmentId
     * @return mixed
     */
    private function fetchSortIndices(ContextModel $context, int $order, string $fragmentId): array
    {
        $indices = $context->fragments()->get()->map(function ($fragment) {
            return $fragment->id;
        })->values()->all();

        array_splice($indices, $order, 0, $fragmentId);

        return $indices;
    }
}
