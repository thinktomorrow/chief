<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Repositories;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentCollection;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;

final class FragmentRepository
{
    private FragmentFactory $fragmentFactory;

    public function __construct(FragmentFactory $fragmentFactory)
    {
        $this->fragmentFactory = $fragmentFactory;
    }

    /**
     * Get entire fragmentCollection for a given context.
     * This is used to render all page fragments.
     */
    public function getFragmentCollection(string $contextId, ?string $fragmentId = null): FragmentCollection
    {
        $fragmentModels = $this->getByContext($contextId);

        $collection = FragmentCollection::fromIterable($fragmentModels, function (Fragment $fragment) {
            $fragment->id = $fragment->getFragmentModel()->id;
            $fragment->parent_id = $fragment->getFragmentModel()->pivot->parent_id;
            $fragment->order = $fragment->getFragmentModel()->pivot->order;

            return $fragment;
        })->sort('order');

        if ($fragmentId) {
            return $collection->find(fn (Fragment $fragment) => $fragment->getFragmentId() == $fragmentId)->getChildNodes();
        }

        return $collection;
    }

    public function findInFragmentCollection(string $contextId, string $fragmentId): Fragment
    {
        $collection = $this->getFragmentCollection($contextId);

        return $collection->find(fn (Fragment $fragment) => $fragment->getFragmentId() == $fragmentId);
    }

    public function getByContext(string $contextId): Collection
    {
        $fragmentModels = ContextModel::findOrFail($contextId)
            ->fragments()
            ->with('assetRelation', 'assetRelation.media')
            ->get();

        return $fragmentModels->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory->create($fragmentModel));
    }

    public function getFragmentIdsByContext(string $contextId): array
    {
        return ContextModel::findOrFail($contextId)
            ->fragments()
            ->pluck('id')
            ->toArray();
    }

    /**
     * Find a fragment for a specific context.
     * This includes the context pivot data
     */
    public function findInContext(string $fragmentId, string $contextId): Fragment
    {
        $fragmentModel = ContextModel::findOrFail($contextId)->fragments()->find($fragmentId);

        if (! $fragmentModel) {
            throw new \Exception('Fragment '.$fragmentId.' not found for context '.$contextId);
        }

        return $this->fragmentFactory->create($fragmentModel);
    }

    public function exists(string $fragmentId): bool
    {
        return FragmentModel::where('id', $fragmentId)->exists();
    }

    public function find(string $fragmentId): Fragment
    {
        return $this->fragmentFactory->create(FragmentModel::findOrFail($fragmentId));
    }

    public function nextId(): string
    {
        $nextId = Uuid::uuid4()->__toString();

        while ($this->exists($nextId)) {
            $nextId = Uuid::uuid4()->__toString();
        }

        return $nextId;
    }
}
