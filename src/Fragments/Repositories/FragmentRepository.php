<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Repositories;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Render\FragmentCollection;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

final class FragmentRepository
{
    private ContextRepository $contextRepository;
    private FragmentFactory $fragmentFactory;

    public function __construct(ContextRepository $contextRepository, FragmentFactory $fragmentFactory)
    {
        $this->contextRepository = $contextRepository;
        $this->fragmentFactory = $fragmentFactory;
    }

    public function getByContext(string $contextId): Collection
    {
        $fragmentModels = ContextModel::findOrFail($contextId)
            ->fragments()
            ->with('assetRelation', 'assetRelation.media')
            ->get();

        return $fragmentModels->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory->create($fragmentModel));
    }

    /**
     * Get entire fragmentCollection for a given context.
     * This is used to render all page fragments.
     */
    public function getTreeByContext(string $contextId): FragmentCollection
    {
        $fragmentModels = $this->getByContext($contextId);

        return FragmentCollection::fromIterable($fragmentModels)->eachRecursive(fn ($node) => $node->getNodeEntry()->setFragmentNode($node));
    }

    public function exists(string $fragmentId): bool
    {
        return FragmentModel::where('id', $fragmentId)->exists();
    }

    public function find(string $fragmentId): Fragment
    {
        return $this->fragmentFactory->create(FragmentModel::findOrFail($fragmentId));
    }

    /**
     * Find a fragment including its context pivot data
     */
    public function findByContext(string $fragmentId, string $contextId): Fragment
    {
        $fragmentModel = ContextModel::findOrFail($contextId)->fragments()->find($fragmentId);

        return $this->fragmentFactory->create($fragmentModel);
    }

    public function nextId(): string
    {
        // We would like to use uuid like (Uuid::uuid4()->__toString()); but the Asset library currently accepts integer(11) as entity_id in database
        $nextId = Uuid::uuid4()->__toString();

        while (FragmentModel::find($nextId)) {
            $nextId = Uuid::uuid4()->__toString();
        }

        return $nextId;
    }

    /**
     * @deprecated use GetByContext instead
     */
    public function getByOwner(ReferableModel $owner): Collection
    {
        throw new \Exception('No more usage of FragmentRepository::getByOwner');

        if (! $context = $this->contextRepository->findByOwner($owner)) {
            return collect();
        }

        return $context->fragments()
            ->get()
            ->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory->create($fragmentModel));
    }
}
