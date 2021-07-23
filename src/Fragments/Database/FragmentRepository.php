<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class FragmentRepository
{
    private Collection $prefetchedRecords;

    public function __construct()
    {
        $this->prefetchedRecords = collect();
    }

    /**
     * @param Model $owner
     *
     * @return Collection Fragmentable[]
     */
    public function getByOwner(Model $owner): Collection
    {
        if (! $context = ContextModel::ownedBy($owner)) {
            return collect();
        }

        $fragmentModels = $context->fragments()->get();

        $this->prefetchRecords($fragmentModels);

        return $fragmentModels->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory($fragmentModel));
    }

    public function getAllShared(FragmentsOwner $owner, array $filters = []): Collection
    {
        $isFilteringByType = isset($filters['types']) && count($filters['types']) > 0;
        $isFilteringByOwner = isset($filters['owners']) && count($filters['owners']) > 0;

        $builder = FragmentModel::query();

        $builder->limit(10);

        if ($isFilteringByType) {
            $builder = $this->filterByTypes($builder, $filters['types']);
        } else {
            $builder = $this->filterByTypes($builder, $owner->allowedFragments());
        }

        if ($isFilteringByOwner) {
            $builder = $this->filterByOwners($builder, $filters['owners']);
        }

        // Default only top shared ones
        if (! $isFilteringByType && ! $isFilteringByOwner) {
            // Get top used already shared ones...
            $builder = $this->filterByUsage($builder);
        }

        $collection = $builder->get()->map(fn (FragmentModel $fragmentModel) => $this->fragmentFactory($fragmentModel));

        // Make sure we don't return the fragments that are already used by the owner
        if (isset($filters['exclude_own']) && $filters['exclude_own']) {
            $fragmentModelIds = $this->getByOwner($owner->ownerModel())->map(fn ($fragment) => $fragment->fragmentModel())->pluck('id')->toArray();

            return $collection->reject(function ($fragmentable) use ($fragmentModelIds) {
                return in_array($fragmentable->fragmentModel()->id, $fragmentModelIds);
            });
        }

        return $collection;
    }

    private function filterByOwners($builder, array $modelReferences): Builder
    {
        $modelReferences = array_map(fn ($value) => ModelReference::fromString($value), $modelReferences);

        return $builder
            ->join('context_fragment_lookup', 'context_fragments.id', '=', 'context_fragment_lookup.fragment_id')
            ->join('contexts', 'context_fragment_lookup.context_id', '=', 'contexts.id')
            ->select('context_fragments.*')
            ->groupBy('context_fragments.id')
            ->where(function ($query) use ($modelReferences) {
                $query->where(DB::raw("1=0"));

                foreach ($modelReferences as $modelReference) {
                    $query->orWhere(function ($query) use ($modelReference) {
                        $query->where('contexts.owner_type', '=', $modelReference->shortClassName());
                        $query->where('contexts.owner_id', '=', $modelReference->id());
                    });
                }
            });
    }

    private function filterByTypes($builder, array $classReferences): Builder
    {
        $classReferences = $this->expandedClassReferences($classReferences);

        return $builder->where(function ($query) use ($classReferences) {
            $query->where(DB::raw("1=0"));
            foreach ($classReferences as $classReference) {
                $query->orWhere('model_reference', 'LIKE', $classReference. '@%');
            }
        });
    }

    private function filterByUsage($builder): Builder
    {
        return $builder
            ->join('context_fragment_lookup', 'context_fragments.id', '=', 'context_fragment_lookup.fragment_id')
            ->select(['context_fragments.*', DB::raw("count('context_fragment_lookup.fragment_id') AS 'usage'")])
            ->groupBy('context_fragments.id')
            ->orderBy('usage', 'DESC');
    }

    private function expandedClassReferences(array $classNames): array
    {
        $expanded = [];

        foreach ($classNames as $className) {
            $modelReference = ModelReference::fromStatic($className);
            $expanded[] = addSlashes($modelReference->className());
            $expanded[] = $modelReference->shortClassName();
        }

        return $expanded;
    }

    /**
     * @param int $id
     * @return Fragmentable
     */
    public function find($id): Fragmentable
    {
        return $this->fragmentFactory(FragmentModel::findOrFail((int) $id));
    }

    public function exists($id): bool
    {
        return ! is_null(FragmentModel::find((int) $id));
    }

    private function prefetchRecords(Collection $fragmentModels): void
    {
        $fragmentModels->mapToGroups(function (FragmentModel $fragmentModel) {
            return [ModelReference::fromString($fragmentModel->model_reference)->className() => ModelReference::fromString($fragmentModel->model_reference)->id()];
        })->reject(function ($_modelIds, $className) {
            $reflection = new ReflectionClass($className);

            return ! $reflection->isSubclassOf(Model::class);
        })->each(function ($modelIds, $className) {
            $modelIds = $modelIds->filter(fn ($modelId) => $modelId !== 0);
            $records = $className::withoutGlobalScopes()->whereIn('id', $modelIds->toArray())->get();

            $records->each(function ($record) {
                $this->prefetchedRecords[$record->modelReference()->get()] = $record;
            });
        });
    }

    private function fragmentFactory(FragmentModel $fragmentModel): Fragmentable
    {
        return $this->prefetchedRecords
            ->get($fragmentModel->model_reference,  fn () => ModelReference::fromString($fragmentModel->model_reference)->instance())
            ->setFragmentModel($fragmentModel);
    }

    public function nextId(): int
    {
        // We would like to use uuid like (Uuid::uuid4()->__toString()); but the Asset library currently accepts integer(11) as entity_id in database
        $nextId = random_int(1000, 2000000000);

        while (FragmentModel::find($nextId)) {
            $nextId = random_int(1000, 2000000000);
        }

        return $nextId;
    }
}
