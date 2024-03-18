<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Domain\Models;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Thinktomorrow\Chief\Fragments\Fragmentable;
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

    public function getByContext(string $contextId): Collection
    {
        $fragmentModels = ContextModel::findOrFail($contextId)->fragments()->get();

        return $fragmentModels->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory->create($fragmentModel));
    }

    public function exists(string $fragmentId): bool
    {
        return FragmentModel::where('id', $fragmentId)->exists();
    }

    public function find(string $fragmentId): Fragmentable
    {
        return $this->fragmentFactory->create(FragmentModel::findOrFail($fragmentId));
    }

    /**
     * Find a fragment including its context pivot data
     */
    public function findByContext(string $fragmentId, string $contextId): Fragmentable
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
}
