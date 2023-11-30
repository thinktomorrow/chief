<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Resource\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
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

    // TODO: can we avoid this method and use getByContext instead?
    public function getByOwner(ReferableModel $owner, string $locale): Collection
    {
        if (! $context = $this->contextRepository->findByOwner($owner, $locale)) {
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
        return FragmentModel::exists($fragmentId);
    }

    public function find(string $fragmentId): Fragmentable
    {
        return $this->fragmentFactory->create(FragmentModel::findOrFail($fragmentId));
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
