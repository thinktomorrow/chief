<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentAdded;
use Thinktomorrow\Chief\Fragments\Resource\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\ManagedModels\Actions\SortModels;

final class AttachFragment
{
    private SortModels $sortModels;

    public function __construct(SortModels $sortModels)
    {
        $this->sortModels = $sortModels;
    }

    public function handle(string $contextId, string $fragmentId, int $order, array $data = []): void
    {
        $context = ContextModel::findOrFail($contextId);

        // Protect against duplicate addition...
        if ($context->fragments()->where('id', $fragmentId)->exists()) {
            throw new FragmentAlreadyAdded('Fragment [' . $fragmentId . '] was already added to context [' . $context->id . ']');
        }

        $indices = $this->fetchSortIndices($context, $order, $fragmentId);

        $context->fragments()->attach($fragmentId, $data);

        $this->sortModels->handleFragments($contextId, $indices);

        event(new FragmentAdded($fragmentId, $context->id));
    }

    /**
     * @param ContextModel $context
     * @param int $order
     * @param int $fragmentId
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
